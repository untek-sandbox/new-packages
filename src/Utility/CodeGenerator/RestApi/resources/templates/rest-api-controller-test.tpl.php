<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $endpoint
 * @var string $method
 */

?>

namespace <?= $namespace ?>;

use Tests\RestApi\Abstract\AbstractRestApiTestCase;
use Untek\Framework\RestApiTest\Asserts\RestApiResponseAssert;

class <?= $className ?> extends AbstractRestApiTestCase
{

    protected function fixtures(): array
    {
        return [
            // TODO: Add fixtures to fill tables in database
        ];
    }

    public function testExample()
    {
        self::markTestIncomplete('Auto-test needs improvement');

        // TODO: Improve the test and add even more test cases

        $data = [

        ];
        $response = $this->sendRequest('<?= $endpoint ?>', '<?= $method ?>', $data);

        (new RestApiResponseAssert($response))
            ->assertStatus(200)
            ->assertData([
            ]);
    }
}