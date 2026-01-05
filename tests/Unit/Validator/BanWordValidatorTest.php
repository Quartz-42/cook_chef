<?php

namespace App\Tests\Unit\Validator;

use App\Validator\BanWord;
use App\Validator\BanWordValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class BanWordValidatorTest extends TestCase
{
    #[DataProvider('provideBannedWords')]
    public function testValidateWithBannedWord(string $input, string $expectedBannedWord): void
    {
        $validator = new BanWordValidator();
        
        $context = $this->createMock(ExecutionContextInterface::class);
        $violationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        
        $context->expects($this->once())
            ->method('buildViolation')
            ->with('This content contains the banned word "{{ banWord }}".')
            ->willReturn($violationBuilder);
            
        $violationBuilder->expects($this->once())
            ->method('setParameter')
            ->with('{{ banWord }}', $expectedBannedWord)
            ->willReturnSelf();
            
        $violationBuilder->expects($this->once())
            ->method('addViolation');

        $validator->initialize($context);

        $constraint = new BanWord();
        $constraint->banWords = ['spam', 'casino'];
        
        $validator->validate($input, $constraint);
    }

    public static function provideBannedWords(): iterable
    {
        yield 'spam' => ['This is a spam message', 'spam'];
        yield 'casino' => ['This is a casino message', 'casino'];
    }

    public static function provideAllowedWords(): iterable
    {
        yield 'clean' => ['This is a clean message'];
        yield 'hello' => ['Hello world'];
        yield 'symfony' => ['I love Symfony'];
    }

    #[DataProvider('provideAllowedWords')]
    public function testValidateWithoutBannedWord(string $input): void
    {
        $validator = new BanWordValidator();
        $context = $this->createMock(ExecutionContextInterface::class);
        
        // On s'attend à ce que buildViolation ne soit JAMAIS appelé
        $context->expects($this->never())->method('buildViolation');

        $validator->initialize($context);

        $constraint = new BanWord();
        $constraint->banWords = ['spam', 'casino'];
        
        $validator->validate($input, $constraint);
    }
}
