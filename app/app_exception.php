<?php
class AppException extends Exception
{
}
class ValidationException extends AppException
{
}
class NotFoundException extends AppException
{
}
class RecordNotFoundException extends ValidationException
{
}
class RecordFoundException extends ValidationException
{
}