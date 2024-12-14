<?php
class StoreValidation
{
    private $data;
    private $image;
    private $errors = [];
    private $skipImageCheck;

    public function __construct(array $data, array $image, bool $skipImageCheck = false)
    {
        $this->image = $image;
        $this->data = $data;
        $this->skipImageCheck = $skipImageCheck;
    }

    public function validate(): array
    {
        $this->checkStoreName();
        $this->checkStoreAddress();
        $this->checkStorePhoneNumber();
        $this->checkCategory();
        $this->checkImage();
        return $this->errors;
    }

    private function checkStoreName(): void
    {
        $storeName = trim($this->data['storeName']);
        if (empty($storeName)) {
            $this->addError('storeName', 'Store name cannot be empty!');
        } else if (!preg_match('/^[a-zA-Z0-9 ]{2,25}$/', $storeName)) {
            $this->addError('storeName',  "Store name must be 2-25 chars & Alphanumeric.");
        }
    }


    private function checkStoreAddress(): void
    {
        $storeAddress = trim($this->data['storeAddress']);
        if (empty($storeAddress)) {
            $this->addError('storeAddress', 'Address cannot be empty!');
        }
        // } else if (!preg_match('/^\d+\s[A-z]+\s[A-z]+\s*(?:APT\s|SUITE\s|#\s)?\d*\s*[A-z\s]+\s*[A-z]+\s*\d{5}(?:-\d{4})?$/', $storeAddress)) {
        //     $this->addError('storeAddress',  'Enter a valid address.');
        // }
    }


    private function checkStorePhoneNumber(): void
    {
        $storePhoneNumber = trim($this->data['storePhoneNumber']);
        if (empty($storePhoneNumber)) {
            $this->addError('storePhoneNumber', 'Phone Number cannot be empty!');
        }
        // else if (!preg_match('/^(\+?\d{1,3}\s)?[0-9]{10}$/', $storePhoneNumber)) {
        //     $this->addError('storePhoneNumber',  'Enter a valid phone number.');
        // }
    }

    private function checkCategory(): void
    {
        if (empty($this->data['category'])) {
            $this->addError('category', 'Category cannot be empty!');
        }
    }

    private function checkImage(): void
    {
        if ($this->skipImageCheck) return;
        $image = $this->image;
        if ($image['storeImage']['error'] == UPLOAD_ERR_NO_FILE) {
            $this->addError('image', 'Image cannot be empty!');
        } else {
            $allowedExtensions = array("jpg", "jpeg", "png", "svg");
            $imageExtension = strtolower(pathinfo($image['storeImage']['name'], PATHINFO_EXTENSION));
            if (!in_array($imageExtension, $allowedExtensions)) {
                $this->addError('image', 'You have to use a valid image extension!');
            }
        }
    }

    private function addError($key, $msg): void
    {
        $this->errors[$key] = $msg;
    }
}
