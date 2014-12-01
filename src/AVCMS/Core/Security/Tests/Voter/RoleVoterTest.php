<?php
/**
 * User: Andy
 * Date: 01/12/14
 * Time: 14:36
 */

namespace AVCMS\Core\Security\Tests\Voter;

use AVCMS\Core\Security\Tests\Fixtures\UserFixture;
use AVCMS\Core\Security\Voter\RoleVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class RoleVoterTest extends \PHPUnit_Framework_TestCase
{
    public function testSupportsClass()
    {
        $voter = new RoleVoter();

        $this->assertTrue($voter->supportsClass('Foo'));
    }

    /**
     * @dataProvider getVoteTests
     */
    public function testVote($roles, $attributes, $expected)
    {
        $voter = new RoleVoter();

        $this->assertSame($expected, $voter->vote($this->getToken($roles), null, $attributes));
    }

    public function getVoteTests()
    {
        return array(
            array(array(), array(), VoterInterface::ACCESS_ABSTAIN),
            array(array(), array('FOO'), VoterInterface::ACCESS_ABSTAIN),
            array(array(), array('ROLE_FOO'), VoterInterface::ACCESS_DENIED),
            array(array('ROLE_FOO'), array('ROLE_FOO'), VoterInterface::ACCESS_GRANTED),
            array(array('ROLE_FOO'), array('FOO', 'ROLE_FOO'), VoterInterface::ACCESS_GRANTED),
        );
    }

    protected function getToken(array $roles)
    {
        $user = new UserFixture();
        $user->roleList = implode(', ', $roles);

        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $token->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        return $token;
    }
}
 