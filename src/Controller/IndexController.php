<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;




class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(Request $request)
    {


        $session = new Session();
        $session->start();
        $session->set('url', '');

        $form = $this->createFormBuilder()
            ->add('url', TextType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formdata = $form->getData();
            $url = $formdata['url'];
            $url = strstr($url, basename($url), true);
            $session->set('url', $url);

            
            $data = file_get_contents($formdata['url']);
            $domain = $formdata['url'];

            $filesystem = new Filesystem();

            $root = 'downloads/'.date('Ymd').'_'. preg_replace('/[^a-zA-Z0-9]+/', '_', parse_url( $url, PHP_URL_HOST )).'/';
            $filesystem = new Filesystem();
    
            try {
                $filesystem->mkdir(
                    Path::normalize($root),
                );
                $filesystem->dumpFile($root.'default.html', $data);
            } catch (IOExceptionInterface $exception) {
                die( "Error create index ".$exception->getPath() );
            }

            return new RedirectResponse($root);

        }


        return $this->renderForm('form.html.twig', [
            'form' => $form,
        ]);

    }
}
