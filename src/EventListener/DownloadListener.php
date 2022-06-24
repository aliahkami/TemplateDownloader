<?PHP
namespace App\EventListener;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class DownloadListener
{
    public function onKernelException(ExceptionEvent $event)
    {

        $request = $event->getRequest(); 
       
        if($request->getRequestUri() !== '/'){


            $session = new Session();
    
            $url = $session->get('url');
            if(!$url){
                die('problem load url ');
            }
    
            $filesystem = new Filesystem();


            
            $local_dir =  dirname( parse_url( $request->getPathInfo() , PHP_URL_PATH ) );

            $path = $request->getPathInfo();
            $paths = explode('/',$path);

            $dir = '/'.$paths[1].'/'.$paths[2].'/';
            $domain = parse_url( $url, PHP_URL_SCHEME ).'://'.parse_url( $url, PHP_URL_HOST );

            $destination =  '.'.$request->getPathInfo();

            $sourcePath = trim(str_replace($dir,'',$path),'/');
            $source = $domain.'/'.$sourcePath;
            
            /*
            print 'url -> '.$url.'<hr>';
            print 'path -> '.$path.'<hr>';
            print 'dir -> '.$dir.'<hr>';
            print 'domain -> '.$domain.'<hr>';
            print 'source -> '.$source.'<hr>';
            print 'destination -> '.$destination.'<hr>';
            dd();
            */
            
            try {
                $filesystem->copy($source, $destination);
    
            } catch (IOExceptionInterface $exception) {
                print 'from:'.$source.' to:'.$destination;
                die( "Error copy file ".$exception->getPath() );
            }

            die();


        }

        


        /*
        if(is_dir($root)){
            try {
                $filesystem->mkdir(
                    Path::normalize($local_dir),
                );
            } catch (IOExceptionInterface $exception) {
                die( "Error create dir ".$exception->getPath() );
            }
        }
        */





    }
}