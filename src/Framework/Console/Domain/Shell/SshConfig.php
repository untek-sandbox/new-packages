<?php

namespace Untek\Framework\Console\Domain\Shell;

class SshConfig
{

    private ?string $host = null;
    private ?string $user = null;
    private int $port = 22;
    private ?string $password = null;
    /**
     * @var string
     * Файл должен содержать примерно следующее: echo Qqqwww111
     */
    private ?string $passwordFile = null;

    /**
     * @return string|null
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * @param string|null $host
     */
    public function setHost(?string $host): void
    {
        $this->host = $host;
    }

    /**
     * @return string|null
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @param string|null $user
     */
    public function setUser(?string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPasswordFile(): ?string
    {
        return $this->passwordFile;
    }

    /**
     * @param string $passwordFile
     */
    public function setPasswordFile(?string $passwordFile): void
    {
        $this->passwordFile = $passwordFile;
    }

}
