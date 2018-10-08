<?php

namespace Spatie\BladeX\Tests\Features\ViewModel;

use Spatie\BladeX\Facades\BladeX;
use Spatie\BladeX\Tests\TestCase;
use Illuminate\Support\Facades\View;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;
use Spatie\BladeX\Tests\Features\ViewModel\TestClasses\DummyViewModel;
use Spatie\BladeX\Tests\Features\ViewModel\TestClasses\InvalidViewModel;
use Spatie\BladeX\Tests\Features\ViewModel\TestClasses\SelectFieldViewModel;

class ViewModelTest extends TestCase
{
    /** @var DummyViewModel */
    protected $viewModel;

    public function setUp()
    {
        parent::setUp();

        View::addLocation(__DIR__.'/stubs');

        $this->viewModel = new DummyViewModel();
    }

    /** @test */
    public function public_methods_are_listed()
    {
        $array = $this->viewModel->toArray();

        $this->assertArrayHasKey('post', $array);
        $this->assertArrayHasKey('categories', $array);
    }

    /** @test */
    public function public_properties_are_listed()
    {
        $array = $this->viewModel->toArray();

        $this->assertArrayHasKey('property', $array);
    }

    /** @test */
    public function values_are_kept_as_they_are()
    {
        $array = $this->viewModel->toArray();

        $this->assertEquals('title', $array['post']->title);
    }

    /** @test */
    public function callables_can_be_stored()
    {
        $array = $this->viewModel->toArray();

        $this->assertEquals('foo', $array['callableMethod']('foo'));
    }

    /** @test */
    public function ignored_methods_are_not_listed()
    {
        $array = $this->viewModel->toArray();

        $this->assertArrayNotHasKey('ignoredMethod', $array);
    }

    /** @test */
    public function to_array_is_not_listed()
    {
        $array = $this->viewModel->toArray();

        $this->assertArrayNotHasKey('toArray', $array);
    }

    /** @test */
    public function magic_methods_are_not_listed()
    {
        $array = $this->viewModel->toArray();

        $this->assertArrayNotHasKey('__construct', $array);
    }

    /** @test */
    public function it_can_register_a_component_with_a_view_model()
    {
        BladeX::component('components.selectField')->viewModel(SelectFieldViewModel::class);

        $registeredComponents = BladeX::registeredComponents();

        $this->assertCount(2, $registeredComponents);
        $this->assertEquals(SelectFieldViewModel::class, $registeredComponents[1]->viewModel);
    }

    /** @test */
    public function it_can_render_a_component_using_a_view_model()
    {
        BladeX::component('components.selectField')->viewModel(SelectFieldViewModel::class);

        $this->assertMatchesViewSnapshot('viewModel');
    }

    /** @test */
    public function it_will_return_an_exception_if_a_view_model_class_does_not_exist()
    {
        $this->expectException(CouldNotRegisterComponent::class);

        BladeX::component('components.selectField')->viewModel('non-existing-class');
    }

    /** @test */
    public function it_will_return_an_exception_if_a_view_model_class_does_not_implement_arrayable()
    {
        $this->expectException(CouldNotRegisterComponent::class);

        BladeX::component('components.selectField')->viewModel(InvalidViewModel::class);
    }
}
