<?php

namespace MHMJsonDB;

use PHPUnit\Framework\TestCase;
use MHMJsonDB\MHMJsonDB;

class MHMJsonDBTest extends TestCase
{
    private $db;
    private $testName = 'test_mhmjsondb.test';

    protected function setUp(): void
    {
        // Create a new instance of MHMJsonDB for testing
        $this->db = new MHMJsonDB($this->testName);
    }

    protected function tearDown(): void
    {
        // Clean up the test file after each test
        if (file_exists($this->testName)) {
            unlink($this->testName);
        }
    }

    public function testInsert()
    {
        $record = ['name' => 'Alice', 'email' => 'alice@example.com'];
        $result = $this->db->insert($record);

        $this->assertArrayHasKey('id', $result);
        $this->assertEquals($record['name'], $result['name']);

        
        $record = ['name' => 'Alice', 'email' => 'alice@example.com'];
        $result = $this->db->insertTable('test', $record);

        $this->assertNotNull($result['test']);
        $this->assertArrayHasKey('id', $result['test']);
        $this->assertEquals($record['name'], $result['test']['name']);
    }

    public function testSelect()
    {
        $this->db->insert(['name' => 'dodo', 'email' => 'bob@example.com']);
        $this->db->insert(['name' => 'dodo', 'email' => 'charlie@example.com']);

        $result = $this->db->selectOne(['name' => 'dodo']);
        $this->assertEquals('bob@example.com', $result['email']);

        $result = $this->db->select(['name' => 'dodo']);
        $this->assertCount(2, $result);
        $this->assertEquals('charlie@example.com', $result[1]['email']);

        $this->db->insert(['name' => 'dodopo', 'email' => 'dodopo@example.com']);
        $this->db->insert(['name' => 'dodoso', 'email' => 'dodoso@example.com']);

        $result = $this->db->select(['name' => 'dodoso']);
        $this->assertCount(1, $result);
    }

    public function testUpdate()
    {
        $record = $this->db->insert(['name' => 'Frank', 'email' => 'frank@example.com']);
        $updated = $this->db->update(['id' => $record['id']], ['email' => 'frank.updated@example.com']);
        $this->assertTrue($updated);


        $result = $this->db->selectOne(['email' => 'frank.updated@example.com']);
        $this->assertEquals('Frank', $result['name']);

        $updated = $this->db->update(['id' => 'nonexistent'], ['email' => 'notupdated@example.com']);
        $this->assertFalse($updated);
    }

    public function testDelete()
    {
        $cnt = count($this->db->select()??[]);
        
        $this->db->insert(['name' => 'Grace0', 'email' => 'grace0@example.com']);
        $this->db->insert(['name' => 'Grace1', 'email' => 'grace1@example.com']);
        $this->db->insert(['name' => 'Grace2', 'email' => 'grace2@example.com']);
        $this->db->insert(['name' => 'Grace_', 'email' => 'grace3@example.com']);
        $this->db->insert(['name' => 'Grace_', 'email' => 'grace4@example.com']);
        $record = $this->db->insert(['name' => 'Grace_', 'email' => 'grace5@example.com']);
        $cnt += 6;

        $deleted = $this->db->delete(['id' => $record['id']]);
        $cnt -=$deleted;
        $this->assertEquals(1, $deleted);

        $deleted = $this->db->delete(['name' => "Grace1"]);
        $cnt -=$deleted;
        $this->assertEquals(1, $deleted);

        $deleted = $this->db->delete(['name' => "Grace_"], false);
        $cnt -=$deleted;
        $this->assertEquals(2, $deleted);

        $result = $this->db->select();
        $this->assertCount($cnt, $result);

        // NonExistentRecord
        $result = $this->db->select(['id' => $record['id']]);
        $this->assertCount(0, $result);

        $deleted = $this->db->delete(['id' => 'nonexistent']);
        $this->assertEquals(0, $deleted);

        $deleted = $this->db->delete();
        $this->assertGreaterThan(1, $deleted);
    }

    public function testSelectDeleteAll()
    {
        for ($i = 0; $i < 10; $i++) {
            $record = $this->db->insert(['name' => 'dodo' . $i, 'email' => 'grace' . $i . '@example.com']);
        }

        $selected = $this->db->select();
        $this->assertCount(10, $selected);

        $deleted = $this->db->delete();
        $this->assertEquals(10, $deleted);

        $selected = $this->db->select();
        $this->assertCount(0, $selected);

        $selected = $this->db->selectOne();
        $this->assertCount(false, $selected);
    }

    public function testUnAllowedChars()
    {
        $result = $this->db->insert(['name' => 'do"do', 'email' => 'grace\""@example.com']);
        $this->assertEquals($result, $this->db->selectOne(['name' => 'do"do', 'email' => 'grace\""@example.com']));
        $this->assertEquals($result, $this->db->select(['name' => 'do"do', 'email' => 'grace\""@example.com'])[0]);
    }

    public function testComplicated()
    {
        for ($i = 0; $i < 20; $i++) {
            $record = $this->db->insert(['name' => 'dodo' . $i, 'email' => 'grace' . $i . '@example.com']);
        }

        for ($i = 0; $i < 100; $i++) {
            $selected = $this->db->select();
            $cnt = count($selected);
            $rand = rand(0, $cnt - 1);

            if ($rand % 3 == 0) {
                // update
                $result = $this->db->update(['id' => $selected[$rand]['id']], ['name' => 'update' . $i, 'email' => 'update' . $i . '@domain']);
                $this->assertTrue($result);
            } else if ($rand % 4 == 0) {
                // reconnect
                $this->db = new MHMJsonDB($this->testName);
            } else if ($rand % 2 == 0) {
                // insert
                $result = $this->db->insert(['name' => 'insert' . $i, 'email' => 'insert' . $i . '@domain']);
                $this->assertIsArray($result);
                if ($result) {
                    $cnt++;
                }
            } else {
                // delete
                $result = $this->db->delete(['id' => $selected[$rand]['id']]);
                $this->assertEquals(1, $result);
                if ($result) {
                    $cnt--;
                }
            }
        }
    }
}
