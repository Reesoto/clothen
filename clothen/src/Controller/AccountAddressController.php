<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Service\AddressService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    /**
     * @Route("/account/address", name="account_address")
     */
    public function index(): Response
    {
        return $this->render('account/address.html.twig');
    }

    /**
     * @Route("/account/add-address", name="account_address_add")
     */
    public function add(Request $request, AddressService $addressService): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            $addressService->save($address);

            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_form.html.twig', [
            "form"  => $form->createView()
        ]);
    }

    /**
     * @Route("/account/edit-address/{id}", name="account_address_edit")
     */
    public function edit(Request $request, AddressService $addressService, int $id): Response
    {
        $address = $addressService->get($id);
        if(!$addressService->isAllow($address, $this->getUser())) {
            return $this->redirectToRoute('account_address');
        }
        $form = $this->createForm(AddressType::class, $address);

        $form = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $addressService->saveEdit($address);

            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_form.html.twig', [
            "form"  => $form->createView()
        ]);
    }

    /**
     * @param AddressService $addressService
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|void
     * @Route("/account/delete-address/{id}", name="account_address_delete")
     */
    public function delete(AddressService $addressService, int $id) : Response
    {
        $address = $addressService->get($id);
        if(!$address || !$addressService->isAllow($address, $this->getUser())) {
            return $this->redirectToRoute('account_address');
        } else {
            $result = $addressService->delete($address, $this->getUser());

            if(!$result) {
                return $this->redirectToRoute('account_address');
            } else {
                return $this->redirectToRoute('account_address');
            }
        }
    }
}
