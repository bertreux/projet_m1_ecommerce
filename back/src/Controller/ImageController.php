<?php

namespace App\Back\Controller;

use App\Back\Entity\Image;
use App\Back\Form\ImageType;
use App\Back\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/image')]
class ImageController extends BackAbstractController
{
    #[Route('/', name: 'app_image_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('image/index.html.twig', [
            'images' => $this->imageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_image_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $image = new Image();
        $form = $this->createFormImageAndTestExtensionImage($request, $image, true);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $fileSystem = new Filesystem();
            $file = $form['url']->getData();
            return $this->moveImageAndSetPrincipal($file, $fileSystem, $image, $data);
        }

        return $this->renderForm('image/new.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_image_show', methods: ['GET'])]
    public function show(Image $image): Response
    {
        return $this->render('image/show.html.twig', [
            'image' => $image,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_image_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Image $image): Response
    {
        $oldFile = $image->getUrl();
        $form = $this->createFormImageAndTestExtensionImage($request, $image, false, $oldFile);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if($form['url']->getData() == $oldFile) {
                $this->setPrincipalNew($data, $image);
                return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
            } else {
                unlink($oldFile);
                unlink('../../front/public/'.$oldFile);
                $fileSystem = new Filesystem();
                $file = $form['url']->getData();
                return $this->moveImageAndSetPrincipal($file, $fileSystem, $image, $data);
            }
        }

        return $this->renderForm('image/edit.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_image_delete', methods: ['POST'])]
    public function delete(Request $request, Image $image): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            unlink($image->getUrl());
            unlink('../../front/public/'.$image->getUrl());
            $this->imageRepository->remove($image, true);
        }

        return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param $file
     * @param Filesystem $fileSystem
     * @param Image $image
     * @param mixed $data
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function moveImageAndSetPrincipal($file, Filesystem $fileSystem, Image $image, mixed $data): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $fileName = rand(1, 999999999) . '.' . $file->getClientOriginalExtension();
        $fileSystem->copy($file->getPathname(), 'images/' . $fileName);
        $file->move('../../front/public/images', $fileName);
        $image->setUrl('images/' . $fileName);

        $this->setPrincipalNew($data, $image);
        return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
    }

    public function setPrincipalNew($data, $image)
    {
        $imageP = $this->imageRepository->findOneBy([
            'principal' => true,
            'produit' => $data->getProduit(),
            'categorie' => $data->getCategorie(),
        ]);
        if ($imageP == null) {
            $image->setPrincipal(true);
        } else {
            if ($data->isPrincipal() == true) {
                $imageP->setPrincipal(false);
                $this->imageRepository->save($imageP, true);
            }
        }

        $this->imageRepository->save($image, true);

    }

    /**
     * @param Request $request
     * @param Image $image
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createFormImageAndTestExtensionImage(Request $request, Image $image, $imageRequired, $oldFile = null) {
        $form = $this->createForm(ImageType::class, $image, [
            'imageRequired' => $imageRequired,
            'dataImage' => $oldFile
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form['url']->getData() != $oldFile) {
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                $file = $form['url']->getData();
                $originalExtension = $file->getClientOriginalExtension();
                if (!in_array($originalExtension, $allowedExtensions)) {
                    $form->get('url')->addError(new FormError("L'image doit avoir comme extension jpg, png ou jpeg"));
                }
            }

        }
        return $form;
    }
}
