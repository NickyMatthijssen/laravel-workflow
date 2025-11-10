<?php

declare(strict_types=1);

namespace ShinobiZero\LaravelWorkflow\Tests\Unit\Service;

use ShinobiZero\LaravelWorkflow\Data\RegisteredWorkflowInterface;
use ShinobiZero\LaravelWorkflow\Service\WorkflowLoaderInterface;
use ShinobiZero\LaravelWorkflow\Service\WorkflowManager;
use stdClass;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\SupportStrategy\InstanceOfSupportStrategy;
use Symfony\Component\Workflow\WorkflowInterface;

test('if the manager adds workflows to the registry', function () {
    $registeredWorkflow = $this->createMock(RegisteredWorkflowInterface::class);
    $registeredWorkflow->expects($this->once())->method('getSupports')->willReturn([stdClass::class]);
    $registeredWorkflow->expects($this->once())->method('toWorkflow')->willReturn($this->createStub(WorkflowInterface::class));

    $workflowLoader = $this->createMock(WorkflowLoaderInterface::class);
    $workflowLoader->expects($this->once())->method('load')->willReturn([$registeredWorkflow]);

    $registry = $this->createMock(Registry::class);
    $registry->expects($this->once())->method('addWorkflow')->with($this->createStub(WorkflowInterface::class), new InstanceOfSupportStrategy(stdClass::class));

    expect((new WorkflowManager($registry, $workflowLoader))->getRegisteredWorkflows())->toBe([$registeredWorkflow]);
});

it('can get a workflow from the registry through the manager', function () {
    $workflow = $this->createStub(WorkflowInterface::class);

    $registry = $this->createMock(Registry::class);
    $registry->expects($this->once())->method('get')->with(new stdClass(), null)->willReturn($workflow);

    expect(
        (new WorkflowManager($registry, $this->createStub(WorkflowLoaderInterface::class)))->get(new stdClass()),
    )->toBe($workflow);
});

it('can transition a workflow from the registry through the manager', function () {
    $workflow = $this->createMock(WorkflowInterface::class);
    $workflow->expects($this->once())->method('can')->with(new stdClass(), 'transition')->willReturn(true);

    $registry = $this->createMock(Registry::class);
    $registry->expects($this->once())->method('get')->with(new stdClass(), null)->willReturn($workflow);

    expect(
        (new WorkflowManager(
            $registry,
            $this->createStub(WorkflowLoaderInterface::class),
        ))->can(new stdClass(), 'transition'),
    )->toBeTrue();
});

it('can apply a workflow transition from the registry through the manager', function () {
    $workflow = $this->createMock(WorkflowInterface::class);
    $workflow->expects($this->once())->method('apply')->with(new stdClass(), 'transition')->willReturn($this->createStub(Marking::class));

    $registry = $this->createMock(Registry::class);
    $registry->expects($this->once())->method('get')->with(new stdClass(), null)->willReturn($workflow);

    expect(
        (new WorkflowManager(
            $registry,
            $this->createStub(WorkflowLoaderInterface::class),
        ))->apply(new stdClass(), 'transition'),
    )->toEqual($this->createStub(Marking::class));
});

it('can return all registered workflows', function () {
    $registeredWorkflow = $this->createStub(RegisteredWorkflowInterface::class);

    $workflowLoader = $this->createMock(WorkflowLoaderInterface::class);
    $workflowLoader->expects($this->once())->method('load')->willReturn([$registeredWorkflow]);

    expect(
        (new WorkflowManager(
            $this->createStub(Registry::class),
            $workflowLoader,
        ))->getRegisteredWorkflows(),
    )->toBe([$registeredWorkflow]);
});
