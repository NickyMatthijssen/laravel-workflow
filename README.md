# Laravel Workflow

[![Latest Version on Packagist](https://img.shields.io/packagist/v/:vendor_slug/:package_slug.svg?style=flat-square)](https://packagist.org/packages/:vendor_slug/:package_slug)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/:vendor_slug/:package_slug/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/:vendor_slug/:package_slug/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/:vendor_slug/:package_slug/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/:vendor_slug/:package_slug/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/:vendor_slug/:package_slug.svg?style=flat-square)](https://packagist.org/packages/:vendor_slug/:package_slug)

Laravel Workflow provides a lightweight, expressive way to model, execute, and monitor stateful workflows in your Laravel applications. Define states, transitions, guards, and side effects using simple PHP classes; run workflows synchronously or queue-driven; and observe progress via events and hooks. Ideal for multi-step business processes (onboarding, order lifecycles, approvals) where clarity and testability matter.

## Installation

You can install the package via composer:

```bash
composer require shinobi-zero/laravel-workflow
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-workflow-config"
```

This is the contents of the published config file:

```php
return [
    /**
     * The default marking store to use for all workflows.
     * This can be overridden on a per-workflow basis.
     * If you want to rename the default marking store, you can do so here.
     */
    'default_marking_store' => ShinobiZero\LaravelWorkflow\MarkingStores\EloquentMarkingStore::class,

    /**
     * The default property to use for all workflows.
     * Useful if every workflow subject uses the same property to store the marking.
     * This way it is not necessary to specify the property on every workflow.
     */
    'default_property' => 'marking',

    /**
     * In case you want to use multiple workflow files, you can add them here, this could be useful if you want to use modules or split it up by feature.
     * The configurations should be in the same format as the 'workflows' key in this file.
     */
    'workflow_paths' => [],

    /**
     * Workflow definitions.
     */
    'workflows' => [],
];
```

## Usage

### 1) Publish and configure workflows
Publish the config and define your workflows:

```php
php artisan vendor:publish --tag="laravel-workflow-config"
```

Edit config/workflow.php and add a workflow definition or add paths to workflow configurations:

```php
[
    'workflow_paths' => [
        base_path('Modules/Orders/config/workflows.php'),
    ], 
    'workflows' => [
        'order' => [
            'type' => Type::StateMachine, // or Type::Workflow
            'supports' => [App\Models\Order::class],
             'initial_place' => 'pending', 
             'places' => [ 'pending', 'paid', 'shipped', 'completed', 'canceled', ], 
             'transitions' => [
                'pay' => ['from' => 'pending', 'to' => 'paid'], 
                'ship' => ['from' => 'paid', 'to' => 'shipped'], 
                'complete' => ['from' => 'shipped', 'to' => 'completed'], 
                'cancel' => ['from' => 'pending', 'to' => 'canceled'],
             ],
             'marking_store' => \Symfony\Component\Workflow\MarkingStore\MethodMarkingStore::class, // optional, default is the one defined in the config under the "default_marking_store" key.
             'property' => 'status', // optional, default is 'marking' or any property defined in the config under the "default_property" key.
        ],
    ],
];
```

Notes:
- supports must list the classes that will use the workflow.
- initial_place is the starting place.
- For a state machine, only a single place is active; for a workflow, multiple places can be active.
- If you use different workflow paths, make sure you only return an array of workflows (exactly like in the "workflows" key of the workflow config).

### 2) Persisting the marking on your model
Add a column to store the marking. For a state machine, a string column is enough; for a workflow, use JSON.

Example migration (state machine):

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->nullable(); // or 'marking'
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
```

If you change the property name, also set 'property' in the workflow config.

### 3) Add helper methods to your model (optional but convenient)
Use the provided trait to interact with the workflow:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use ShinobiZero\LaravelWorkflow\Concerns\InteractsWithWorkflow;

class Order extends Model
{
    use InteractsWithWorkflow;

    protected $fillable = ['marking']; // or your chosen property
}
```

Now you can:

```php
$order = Order::find(1);

// Get the Symfony workflow instance (optional name if multiple workflows support the model)
$workflow = $order->getWorkflow('order');

// Check and apply transitions
if ($order->canTransitionTo('pay', 'order')) {
    $order->applyTransition('pay', 'order');
    $order->save();
}
```

### 4) Using the facade directly
If you prefer not to use the trait:

```php
<?php

use ShinobiZero\LaravelWorkflow\Facade\WorkflowFacade as Workflow;
use App\Models\Order;

$order = Order::find(1);

if (Workflow::can($order, 'ship', 'order')) {
    Workflow::apply($order, 'ship', 'order');
    $order->save();
}
```

### 5) Resolving via the manager (advanced)
You can type-hint the manager to access the underlying Symfony registry:

```php
<?php

use ShinobiZero\LaravelWorkflow\Service\WorkflowManagerInterface;
use App\Models\Order;

public function __invoke(WorkflowManagerInterface $workflows, Order $order)
{
    $wf = $workflows->get($order, 'order');

    if ($wf->can($order, 'complete')) {
        $wf->apply($order, 'complete');
        $order->save();
    }
}
```

### 6) Multiple workflow config files (optional)
Add file paths to workflow.workflow_paths in config/workflow.php. The service provider will merge them and throw a clear error if a path is missing.

### 7) Custom marking store (optional)
- EloquentMarkingStore: stores marking on your Eloquent model (default).
- Symfony’s MethodMarkingStore: used for getters/setters or public properties.

Choose per workflow using the marking_store key.

## Testing

This package is tested with Pest. Run:

```bash
composer test
```

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Nicky Matthijssen](https://github.com/NickyMatthijssen)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
