<?php
class LoginValidation
{
    private $data;
    private $errors = [];
    private static $fields = ["Email", "Password"];

    public function __construct(array $data)
    {
        $this->data = $data;
    }
    public function validate(): array
    {
        $this->validateEmail();
        $this->validatePassword();
        return $this->errors;
    }

    private function validateEmail(): void
    {
        $email = strtolower(trim($this->data[self::$fields[0]]));
        if (empty($email)) {
            $this->addError(self::$fields[0], self::$fields[0] . " cannot be empty!");
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError(self::$fields[0], self::$fields[0] . " must be a valid email address.");
        }
    }

    private function validatePassword(): void
    {
        $password = trim($this->data[self::$fields[1]]);
        if (empty($password)) {
            $this->addError(self::$fields[1], self::$fields[1] . " cannot be empty!");
        } else if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+.])[A-Za-z\d!@#$%^&*()_+.]{8,}$/', $password)) {
            $this->addError(self::$fields[1], self::$fields[1] . " must be between 8 and 20 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.");
        }
    }

    private function addError($key, $msg): void
    {
        $this->errors[$key] = $msg;
    }
}
