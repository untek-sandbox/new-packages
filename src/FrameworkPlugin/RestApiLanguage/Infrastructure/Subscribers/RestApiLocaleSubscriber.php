<?php

namespace Untek\FrameworkPlugin\RestApiLanguage\Infrastructure\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;
use Untek\Component\Translator\Infrastructure\Exceptions\NotFoundLanguageException;

class RestApiLocaleSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private TranslatorInterface $translator,
        private array $languageCodes,
        private string $headerKeyName = 'Language',
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $locale = $event->getRequest()->headers->get($this->headerKeyName);
        if (!empty($locale)) {
            if (!isset($this->languageCodes[$locale])) {
                throw new NotFoundLanguageException('Language not defined in application.');
            }
            $this->translator->setLocale($locale);
        }
    }
}
