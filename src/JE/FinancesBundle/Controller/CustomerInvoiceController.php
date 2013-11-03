<?php

namespace JE\FinancesBundle\Controller;

use JE\FinancesBundle\Entity\CustomerInvoice;
use JE\FinancesBundle\Form\CustomerInvoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\Paginator;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;

class CustomerInvoiceController extends Controller
{
    /** @var  EntityManager */
    private $em;

    /** @var  Request */
    private $request;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @var UploadableManager
     */
    private $uploadableManager;

    /**
     * @Secure(roles="ROLE_TREZ_VIEW")
     * @Template
     */
    public function indexAction($page, $sort, $direction)
    {
        $_GET['sort'] = $sort;
        $_GET['direction'] = $direction;
        $invoices = $this->em->getRepository('JEFinancesBundle:CustomerInvoice')->queryAll();
        $pagination = $this->paginator->paginate($invoices, $page, 40);

        return array(
            'invoices' => $pagination,
        );
    }

    /**
     * @Secure(roles="ROLE_TREZ_ADD")
     * @Template
     */
    public function newAction()
    {
        $invoice = new CustomerInvoice;

        return $this->handleForm($invoice);
    }

    /**
     * @Secure(roles="ROLE_TREZ_EDIT")
     * @Template
     */
    public function editAction(CustomerInvoice $invoice)
    {
        return $this->handleForm($invoice);
    }

    /**
     * @Secure(roles="ROLE_TREZ_EDIT")
     */
    public function paidAction(CustomerInvoice $invoice)
    {
        $invoice->setPaid(true);
        $invoice->setPaidAt(new \DateTime);

        $this->em->persist($invoice);
        $this->em->flush();

        return new Response('success');
    }

    /**
     * @Secure(roles="ROLE_TREZ_EDIT")
     */
    public function unpaidAction(CustomerInvoice $invoice)
    {
        $invoice->setPaid(false);

        $this->em->persist($invoice);
        $this->em->flush();

        return $this->redirect($this->generateUrl('je_finances_fc'));
    }

    private function handleForm(CustomerInvoice $invoice)
    {
        $form = $this->createForm(new CustomerInvoiceType, $invoice);

        if($this->request->isMethod('POST')){
            $form->handleRequest($this->request);

            if($form->isValid()){
                $this->em->persist($invoice);
                if($invoice->getFile()) $this->uploadableManager->markEntityToUpload($invoice, $invoice->getFile());
                $this->em->flush();

                return $this->redirect($this->generateUrl('je_finances_fc'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
