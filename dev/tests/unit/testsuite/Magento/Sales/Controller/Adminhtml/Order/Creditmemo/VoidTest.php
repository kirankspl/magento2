<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Sales\Controller\Adminhtml\Order\Creditmemo;

/**
 * Class VoidTest
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class VoidTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Sales\Controller\Adminhtml\Order\Creditmemo\AddComment
     */
    protected $controller;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $loaderMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $senderMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $responseMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManagerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $sessionMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $actionFlagMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $creditmemoMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $messageManagerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $helperMock;

    /**
     * @var \Magento\Backend\Model\View\Result\RedirectFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultRedirectFactoryMock;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultForwardFactoryMock;

    /**
     * @var \Magento\Backend\Model\View\Result\Redirect|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultRedirectMock;

    /**
     * @var \Magento\Backend\Model\View\Result\Forward|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultForwardMock;

    public function setUp()
    {
        $this->creditmemoMock = $this->getMockBuilder('Magento\Sales\Model\Order\Creditmemo')
            ->disableOriginalConstructor()
            ->setMethods(['getInvoice', 'getOrder', 'cancel', 'getId', 'void', '__wakeup'])
            ->getMock();
        $titleMock = $this->getMockBuilder('Magento\Framework\App\Action\Title')
            ->disableOriginalConstructor()
            ->getMock();
        $this->requestMock = $this->getMockBuilder('Magento\Framework\App\Request\Http')
            ->disableOriginalConstructor()
            ->getMock();
        $this->responseMock = $this->getMockBuilder('Magento\Framework\App\Response\Http')
            ->disableOriginalConstructor()
            ->getMock();
        $this->objectManagerMock = $this->getMock('Magento\Framework\ObjectManagerInterface');
        $this->helperMock = $this->getMockBuilder('Magento\Backend\Helper\Data')
            ->disableOriginalConstructor()
            ->getMock();
        $this->messageManagerMock = $this->getMockBuilder('Magento\Framework\Message\Manager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->sessionMock = $this->getMockBuilder('Magento\Backend\Model\Session')
            ->disableOriginalConstructor()
            ->getMock();
        $this->actionFlagMock = $this->getMockBuilder('Magento\Framework\App\ActionFlag')
            ->disableOriginalConstructor()
            ->getMock();
        $this->contextMock = $this->getMockBuilder('Magento\Backend\App\Action\Context')
            ->disableOriginalConstructor()
            ->getMock();
        $this->loaderMock = $this->getMockBuilder('Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoader')
            ->disableOriginalConstructor()
            ->getMock();
        $this->senderMock = $this->getMockBuilder('Magento\Sales\Model\Order\Email\Sender\CreditmemoSender')
            ->disableOriginalConstructor()
            ->getMock();
        $this->resultRedirectFactoryMock = $this->getMockBuilder('Magento\Backend\Model\View\Result\RedirectFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->resultForwardFactoryMock = $this->getMockBuilder('Magento\Backend\Model\View\Result\ForwardFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->resultRedirectMock = $this->getMockBuilder('Magento\Backend\Model\View\Result\Redirect')
            ->disableOriginalConstructor()
            ->getMock();
        $this->resultForwardMock = $this->getMockBuilder('Magento\Backend\Model\View\Result\Forward')
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($this->requestMock));
        $this->contextMock->expects($this->any())
            ->method('getResponse')
            ->will($this->returnValue($this->responseMock));
        $this->contextMock->expects($this->any())
            ->method('getActionFlag')
            ->will($this->returnValue($this->actionFlagMock));
        $this->contextMock->expects($this->any())
            ->method('getHelper')
            ->will($this->returnValue($this->helperMock));
        $this->contextMock->expects($this->any())
            ->method('getSession')
            ->will($this->returnValue($this->sessionMock));
        $this->contextMock->expects($this->any())
            ->method('getObjectManager')
            ->will($this->returnValue($this->objectManagerMock));
        $this->contextMock->expects($this->any())
            ->method('getTitle')
            ->will($this->returnValue($titleMock));
        $this->contextMock->expects($this->any())
            ->method('getMessageManager')
            ->will($this->returnValue($this->messageManagerMock));

        $objectManager = new \Magento\TestFramework\Helper\ObjectManager($this);
        $this->controller = $objectManager->getObject(
            'Magento\Sales\Controller\Adminhtml\Order\Creditmemo\Void',
            [
                'context' => $this->contextMock,
                'creditmemoLoader' => $this->loaderMock,
                'resultRedirectFactory' => $this->resultRedirectFactoryMock,
                'resultForwardFactory' => $this->resultForwardFactoryMock
            ]
        );
    }

    public function testExecuteNoCreditmemo()
    {
        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->withAnyParameters()
            ->willReturnArgument(0);
        $this->loaderMock->expects($this->once())
            ->method('load')
            ->willReturn(false);
        $this->resultForwardFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->resultForwardMock);
        $this->resultForwardMock->expects($this->once())
            ->method('forward')
            ->with('noroute')
            ->willReturnSelf();

        $this->assertInstanceOf(
            'Magento\Backend\Model\View\Result\Forward',
            $this->controller->execute()
        );
    }

    public function testExecuteModelException()
    {
        $id = 123;
        $message = 'Model exception';
        $e = new \Magento\Framework\Model\Exception($message);

        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->withAnyParameters()
            ->willReturnArgument(0);
        $this->creditmemoMock->expects($this->once())
            ->method('void')
            ->willThrowException($e);
        $this->loaderMock->expects($this->once())
            ->method('load')
            ->willReturn($this->creditmemoMock);
        $this->resultRedirectFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->resultRedirectMock);
        $this->creditmemoMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($id);
        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('sales/*/view', ['creditmemo_id' => $id])
            ->willReturnSelf();

        $this->assertInstanceOf(
            'Magento\Backend\Model\View\Result\Redirect',
            $this->controller->execute()
        );
    }

    public function testExecuteException()
    {
        $id = 321;
        $message = 'Model exception';
        $e = new \Exception($message);

        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->withAnyParameters()
            ->willReturnArgument(0);
        $this->creditmemoMock->expects($this->once())
            ->method('void')
            ->willThrowException($e);
        $this->loaderMock->expects($this->once())
            ->method('load')
            ->willReturn($this->creditmemoMock);
        $this->resultRedirectFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->resultRedirectMock);
        $this->creditmemoMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($id);
        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('sales/*/view', ['creditmemo_id' => $id])
            ->willReturnSelf();

        $this->assertInstanceOf(
            'Magento\Backend\Model\View\Result\Redirect',
            $this->controller->execute()
        );
    }

    public function testExecute()
    {
        $id = '111';

        $transactionMock = $this->getMockBuilder('Magento\Framework\DB\Transaction')
            ->disableOriginalConstructor()
            ->getMock();
        $orderMock = $this->getMockBuilder('Magento\Sales\Model\Order')
            ->disableOriginalConstructor()
            ->getMock();
        $invoiceMock = $this->getMockBuilder('Magento\Sales\Model\Order\Invoice')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->withAnyParameters()
            ->willReturnArgument(0);
        $this->loaderMock->expects($this->once())
            ->method('load')
            ->willReturn($this->creditmemoMock);
        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with('Magento\Framework\DB\Transaction')
            ->willReturn($transactionMock);
        $this->creditmemoMock->expects($this->any())
            ->method('getOrder')
            ->willReturn($orderMock);
        $this->creditmemoMock->expects($this->any())
            ->method('getInvoice')
            ->willReturn($invoiceMock);
        $this->messageManagerMock->expects($this->once())
            ->method('addSuccess')
            ->with('You voided the credit memo.');
        $this->resultRedirectFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->resultRedirectMock);
        $this->creditmemoMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($id);
        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('sales/*/view', ['creditmemo_id' => $id])
            ->willReturnSelf();

        $this->assertInstanceOf(
            'Magento\Backend\Model\View\Result\Redirect',
            $this->controller->execute()
        );
    }
}
