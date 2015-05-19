<?php

namespace DJBlaster\BlasterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use DJBlaster\BlasterBundle\Entity\Customer;
use DJBlaster\BlasterBundle\Form\Type\CustomerType;

class AdminCustomersController extends Controller {
    public function editCustomerAction(Request $request, $customer_id) {
        $em = $this->getDoctrine()->getEntityManager();

        if ($customer_id == 0) {
            $customer = new Customer();
        } else {

            $customer = $em->getRepository('DJBlasterBundle:Customer')->find($customer_id);
        }

        $action = $this->generateUrl('dj_blaster_admin_customer_edit', array('customer_id' => $customer_id));
        $options = array('action' => $action);
        $form = $this->createForm(new CustomerType(), $customer, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                $session = $this->getRequest()->getSession();
                $data = $form->getData();

                $em->persist($data);
                $em->flush();
                $session->getFlashBag()->add('customer-notices', $data->getName() . ' added.');
                return $this->redirect($this->generateUrl('dj_blaster_admin_customer_list'));

            }
        }

        return $this->render('DJBlasterBundle:Admin/Customer:customer_form.html.twig', array(
            'form' => $form->createView(),
            'customer' => $customer,
        ));
    }

    public function listCustomersAction(Request $request) {
        $session = $this->getRequest()->getSession();

        $customers = $this->getDoctrine()->getRepository('DJBlasterBundle:Customer')->findAllOrderedByName();

        $notices = $session->getFlashBag()->get('customer-notices', array());

        return $this->render('DJBlasterBundle:Admin/Customer:customer_list.html.twig', array(
            'customers' => $customers,
            'notices' => $notices,
        ));
    }

    public function deleteCustomerAction(Request $request, Customer $customer) {
        $session = $this->getRequest()->getSession();
        if (!$customer) {
            throw $this->createNotFoundException("No customer found.");
        }

        $session->getFlashBag()->add('customer-notices', $customer->getName() . ' was removed.');

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($customer);
        $em->flush();

        return $this->redirect($this->generateUrl('dj_blaster_admin_customer_list'));
    }

}
