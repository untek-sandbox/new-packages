<?php

namespace Untek\User\AuthenticationWeb\Presentation\Http\Site\Forms;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Untek\Model\Validator\Interfaces\ValidationByMetadataInterface;
use Untek\Component\Web\Form\Interfaces\BuildFormInterface;

class AuthForm implements ValidationByMetadataInterface, BuildFormInterface
{

    private $login;
    private $password;
    private $rememberMe = false;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('login', new Assert\NotBlank);
        $metadata->addPropertyConstraint('password', new Assert\NotBlank);
    }

    public function buildForm(FormBuilderInterface $formBuilder)
    {
        $formBuilder
            ->add('login', TextType::class, [
                'label' => 'Login'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password'
            ])
            ->add('rememberMe', CheckboxType::class, [
                'label' => 'Remember me',
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Send'
            ]);
    }

    protected function t(string $bundle, string $key): string {
        return $bundle . '-' . $key;
//        return I18Next::t($bundle, $key);
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = trim($login);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = trim($password);
    }

    public function getRememberMe(): bool
    {
        return $this->rememberMe;
    }

    public function setRememberMe(bool $rememberMe): void
    {
        $this->rememberMe = $rememberMe;
    }
}