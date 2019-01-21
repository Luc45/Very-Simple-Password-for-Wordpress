<?php

namespace VSPW;

class InterceptorTest extends \Codeception\TestCase\WPTestCase
{
    /** @var Password */
    protected $password;

    public function setUp()
    {
        // before
        parent::setUp();

        // your set up methods here
        $this->password = $this->prophesize('\\VSPW\\Password');
        $this->password->hasPassword()->willReturn(true);
    }

    public function tearDown()
    {
        // your tear down methods here

        // then
        parent::tearDown();
    }

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable()
    {
        $sut = $this->make_instance();
        $this->assertInstanceOf(Interceptor::class, $sut);
    }

    private function make_instance()
    {
        $sut = new Interceptor($this->password->reveal());

        return $sut;
    }

    /**
     * @test
     */
    public function it_should_intercept()
    {
        $this->assertTrue($this->make_instance()->intercept());
    }

    /**
     * @test
     */
    public function it_should_skip_interception_if_password_is_not_set()
    {
        $sut = $this->make_instance();

        $this->password->hasPassword()->willReturn(false);
        $this->assertFalse($sut->intercept());
        $this->assertEquals($sut->skipReason, 'password');
    }

    /**
     * @test
     */
    public function it_should_skip_interception_in_login_page()
    {
        $sut = $this->make_instance();

        add_filter('vspw_is_login_page', function () {
            return true;
        });

        $result = $sut->intercept();
        $this->assertFalse($result);
        $this->assertEquals($sut->skipReason, 'loginPage');
    }

    /**
     * @test
     */
    public function it_should_skip_cli_interception()
    {
        $sut = $this->make_instance();

        add_filter('vspw_is_wpcli', function () {
            return true;
        });

        $result = $sut->intercept();
        $this->assertFalse($result);
        $this->assertEquals($sut->skipReason, 'cli');
    }

    /**
     * @test
     */
    public function it_should_allow_to_skip_interception_with_filters()
    {
        $sut = $this->make_instance();

        add_filter('vspw_should_skip_interception', function () {
            return true;
        });

        $result = $sut->intercept();
        $this->assertFalse($result);
        $this->assertEquals($sut->skipReason, 'filter');
    }

    /**
     * @test
     */
    public function it_should_allow_to_skip_interception_with_capabilities()
    {
        $sut = $this->make_instance();

        add_filter('vspw_capability_skips_interception', function () {
            return 'exist';
        });

        $result = $sut->intercept();
        $this->assertFalse($result);
        $this->assertEquals($sut->skipReason, 'capability');
    }

    /**
     * @test
     */
    public function it_should_allow_cli_interception_override()
    {
        $sut = $this->make_instance();

        add_filter('vspw_is_wpcli', function () {
            return true;
        });

        add_filter('vspw_should_skip_wpcli_interception', function () {
            return false;
        });

        $result = $sut->intercept();
        $this->assertTrue($result);
    }
}