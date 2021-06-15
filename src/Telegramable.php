<?php


namespace Denysovvl;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class Telegramable
 * Send information about application's fails (500 error) to the user's Telegram.
 *
 * Check config/telegramable.php to set up your notifications.
 *
 * @package App\Ext
 */
class Telegramable
{
    private $exception;

    private $config;

    public function __construct(\Throwable $exception)
    {
        $this->exception = $exception;

        $this->config = config('telegramable');

        $this->process();
    }

    /**
     * Returns Exception's class.
     *
     * @return string
     */
    protected function getExceptionClass(): string
    {
        return get_class($this->exception);
    }

    /**
     * Returns Exception's message.
     *
     * @return string
     */
    protected function getMessage(): string
    {
        return $this->exception->getMessage();
    }

    /**
     * Return Exception's file path.
     *
     * @return string
     */
    protected function getFile(): string
    {
        return $this->exception->getFile();
    }

    /**
     * Returns Exception's line in file.
     *
     * @return string
     */
    protected function getLine(): string
    {
        return $this->exception->getLine();
    }

    /**
     * Returns App name form .env file.
     *
     * @return string
     */
    protected function getAppName(): string
    {
        return env('APP_NAME');
    }

    /**
     * Returns 'enabled' value from config/telegramable.php.
     *
     * @return bool
     */
    protected function isNotEnabled(): bool
    {
        return !$this->config['enabled'];
    }

    /**
     * Returns 'exceptions_only' value from config/telegramable.php.
     *
     * @return array
     */
    protected function getExceptionsOnly(): array
    {
        return $this->config['exceptions_only'];
    }

    /**
     * Returns 'exceptions_except' value from config/telegramable.php.
     *
     * @return array
     */
    protected function getExceptionsExcept(): array
    {
        return $this->config['exceptions_except'];
    }

    /**
     * Returns 'trace' value from config/telegramable.php.
     *
     * @return bool
     */
    protected function needTrace(): bool
    {
        return $this->config['trace'];
    }

    /**
     * Returns 'trace_depth' value from config/telegramable.php.
     *
     * @return int
     */
    protected function getTraceDepth(): int
    {
        return $this->config['trace_depth'];
    }

    /**
     * Returns TELEGRAM_BOT_TOKEN from .env file.
     *
     * @return string
     */
    protected function getBotToken(): string
    {
        return $this->config['bot_token'];
    }

    /**
     * Returns TELEGRAM_USER_ID from .env file.
     *
     * @return string
     */
    protected function getUserId(): string
    {
        return $this->config['user_id'];
    }

    /**
     * Returns prepared trace text.
     *
     * @return string
     */
    protected function getTrace(): string
    {
        $traces = array_slice($this->exception->getTrace(), 0, $this->getTraceDepth());
        $text = '';

        $count = 0;
        foreach ($traces as $trace) {
            $text .= "\r\n[{$count}]\r\n";
            foreach ($trace as $key => $value)
            $text .= "{$key} : {$value}\r\n";
            $count++;
        }

        return $text;
    }

    /**
     * Checks if current Exception class is not blocked.
     *
     * @return bool
     */
    protected function shouldSend(): bool
    {
        $only = $this->getExceptionsOnly();
        $except = $this->getExceptionsExcept();

        if (!empty($except) and in_array($this->getExceptionClass(), $except)) {
            return false;
        }

        if (!empty($only) and !in_array($this->getExceptionClass(), $only)) {
            return false;
        }

        return true;
    }

    /**
     * Check if all needed .env variables are set.
     *
     * @return bool
     */
    protected function envVariablesDefined(): bool
    {
        $defined = true;

        if (!env('TELEGRAM_BOT_TOKEN')) {
            Log::error('TELEGRAM_BOT_TOKEN is not set. Check .env file');
            $defined = false;
        }

        if (!env('TELEGRAM_USER_ID')) {
            Log::error('TELEGRAM_USER_ID is not set. Check .env file');
            $defined = false;
        }

        return $defined;
    }

    /**
     * Returns prepared text ready to send to user's Telegram.
     *
     * @return string
     */
    protected function getText()
    {
        $text = "
            ⛔️<b>WARNING ({$this->getAppName()})</b>\r\n<b>Error:</b> <code>{$this->getMessage()}</code>\n<b>File:</b> <code>{$this->getFile()}</code>\n<b>Line:</b> <code>{$this->getLine()}</code>\r\n";


        if ($this->config['trace']) {
            $text .= "<pre>{$this->getTrace()}</pre>";
        }

        return $text;
    }

    /**
     * Sends message to Telegram.
     */
    protected function sendNotification(): void
    {
        $url = 'https://api.telegram.org/bot' . $this->getBotToken() . '/sendMessage';

        $response = Http::get($url, [
            'chat_id' => $this->getUserId(),
            'text' => $this->getText(),
            'parse_mode' => 'HTML'
        ]);

        if ($response->failed()) {
            Log::error("Telegram Client returned Fail status. {$response->body()}");
        }
    }

    /**
     * Starts app.
     */
    protected function process(): void
    {
        if ($this->isNotEnabled() or !$this->exception instanceof \Throwable) {
            return ;
        }

        if ($this->envVariablesDefined() and $this->shouldSend()) {
            $this->sendNotification();
        }
    }


}
