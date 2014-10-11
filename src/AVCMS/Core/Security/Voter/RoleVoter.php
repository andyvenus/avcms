<?php
/**
 * User: Andy
 * Date: 10/10/2014
 * Time: 18:37
 */

namespace AVCMS\Core\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class RoleVoter
{
    private $prefix;

    /**
     * Constructor.
     *
     * @param string $prefix The role prefix
     */
    public function __construct($prefix = 'ROLE_')
    {
        $this->prefix = $prefix;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsAttribute($attribute)
    {
        return 0 === strpos($attribute, $this->prefix);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $result = VoterInterface::ACCESS_ABSTAIN;
        $roles = $this->extractRoles($token);

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            $result = VoterInterface::ACCESS_DENIED;
            foreach ($roles as $role) {
                if ($attribute === $role->getRole()) {
                    return VoterInterface::ACCESS_GRANTED;
                }
            }
        }

        return $result;
    }

    protected function extractRoles(TokenInterface $token)
    {
        return $token->getUser()->getRoles();
    }
}