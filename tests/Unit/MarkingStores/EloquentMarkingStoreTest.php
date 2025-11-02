<?php

declare(strict_types=1);

namespace NickyMatthijssen\LaravelWorkflow\Tests\Unit\MarkingStores;

use NickyMatthijssen\LaravelWorkflow\Exceptions\InvalidSubjectException;
use NickyMatthijssen\LaravelWorkflow\MarkingStores\EloquentMarkingStore;
use NickyMatthijssen\LaravelWorkflow\Tests\Mock\TestModel;
use stdClass;
use Symfony\Component\Workflow\Marking;

it('can get status from magic getter', function (bool $singleState, string|array $initialState, string|array $expectedState) {
    $model = new TestModel(['status' => $initialState]);
    $markingStore = new EloquentMarkingStore($singleState, 'status');

    expect($markingStore->getMarking($model))->toEqual(new Marking($expectedState));
})->with([
    'single state' => [true, 'a', ['a' => 1]],
    'multi state' => [false, ['a' => 1], ['a' => 1]],
]);

it('can set status through magic setter', function (bool $singleState, string|array $initialState, string|array $updatedState, string|array $expectedState) {
    $model = new TestModel(['status' => $initialState]);
    $markingStore = new EloquentMarkingStore($singleState, 'status');

    $markingStore->setMarking($model, new Marking($updatedState));
    expect($model->status)->toEqual($expectedState);
})->with([
    'single state' => [true, 'a',  ['a' => 1], 'a'],
    'multi state' => [false, ['a' => 1], ['b' => 1, 'c' => 1], ['b' => 1, 'c' => 1]],
]);

test('getMarking throws exception when subject is not an Eloquent model', function () {
    (new EloquentMarkingStore(true, 'status'))->setMarking(new stdClass, new Marking([]));

})->throws(InvalidSubjectException::class, 'The subject must be an Eloquent model, "stdClass" given');

test('setMarking throws exception when subject is not an Eloquent model', function () {
    (new EloquentMarkingStore(true, 'status'))->setMarking(new stdClass, new Marking([]));
})->throws(InvalidSubjectException::class, 'The subject must be an Eloquent model, "stdClass" given');
