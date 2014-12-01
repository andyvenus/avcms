<?php
/**
 * User: Andy
 * Date: 01/12/14
 * Time: 13:08
 */

namespace AVCMS\Core\Security\Tests\Voter;

use AVCMS\Core\Security\Tests\Fixtures\RolePermissionsProviderFixture;
use AVCMS\Core\Security\Tests\Fixtures\UserFixture;
use AVCMS\Core\Security\Voter\PermissionsVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class PermissionsVoterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $userRole
     * @param string $attributes
     * @param bool $expected
     * @param bool $permDefault
     * @param bool $adminDefault
     *
     * @dataProvider getVoteTests
     */
    public function testVote($userRole, $attributes, $expected, $permDefault, $adminDefault)
    {
        $user = new UserFixture();
        $user->roleList = $userRole;
        $user->group->permDefault = $permDefault;
        $user->group->adminDefault = $adminDefault;

        $voter = new PermissionsVoter($this->getPermissionsProvider());

        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $token->expects($this->any())
            ->method('getUser')
            ->willReturn($user);

        $vote = $voter->vote($token, null, (array) $attributes);

        $this->assertEquals($expected, $vote);
    }

    public function getVoteTests()
    {
        return array(
            array('ROLE_ADMIN', 'PERM_ADMIN_ONLY', VoterInterface::ACCESS_GRANTED, 'allow', 'allow'),
            array('ROLE_ADMIN', 'PERM_ADMIN_FALSE', VoterInterface::ACCESS_DENIED, 'allow', 'allow'),
            array('ROLE_ADMIN', 'ADMIN_PERM_ALLOW', VoterInterface::ACCESS_GRANTED, 'deny', 'deny'),
            array('ROLE_ADMIN', 'ADMIN_PERM_NOT_DEFINED', VoterInterface::ACCESS_DENIED, 'deny', 'deny'),
            array('ROLE_USER', 'ADMIN_PERM_ALLOW', VoterInterface::ACCESS_DENIED, 'deny', 'deny'),
            array('ROLE_ADMIN', 'PERM_NOT_DEFINED', VoterInterface::ACCESS_GRANTED, 'allow', 'allow'),
            array('ROLE_ADMIN', 'PERM_NOT_DEFINED', VoterInterface::ACCESS_DENIED, 'deny', 'allow'),
            array('ROLE_SUPER_ADMIN', 'PERM_NOT_DEFINED', VoterInterface::ACCESS_GRANTED, 'deny', 'deny'), // super admin, always allow
            array('ROLE_BANNED', 'PERM_NOT_DEFINED', VoterInterface::ACCESS_DENIED, 'allow', 'allow'), // banned, always deny
            array('ROLE_ADMIN', 'NOT_SUPPORTED', VoterInterface::ACCESS_ABSTAIN, 'allow', 'allow'),
        );
    }

    public function testSupportsClass()
    {
        $voter = new PermissionsVoter($this->getPermissionsProvider());

        $this->assertFalse($voter->supportsClass('stdClass'));
    }

    protected function getPermissionsProvider()
    {
        $provider = new RolePermissionsProviderFixture();
        $provider->permissions = [
            'ROLE_ADMIN' => [
                'PERM_ADMIN_ONLY' => true,
                'PERM_ADMIN_FALSE' => false,
                'ADMIN_PERM_ALLOW' => true
            ],
            'ROLE_USER' => [
                'PERM_ADMIN_ONLY' => false,
                'PERM_ADMIN_FALSE' => true
            ]
        ];

        return $provider;
    }
}
 