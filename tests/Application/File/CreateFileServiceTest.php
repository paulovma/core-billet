<?php

namespace App\Tests\Application\File;

use App\Application\File\CreateFileService;
use App\Domain\File\File;
use App\Domain\File\FileRepository;
use App\Domain\File\FileStorer;
use App\Domain\File\ProcessFileMessageSender;
use App\Domain\File\Validator\ValidatorChain;
use GuzzleHttp\Promise\Utils;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use function GuzzleHttp\Promise\any;

class CreateFileServiceTest extends TestCase
{
    private FileStorer $s3Uploader;
    private ProcessFileMessageSender $sqsMessageSender;
    private FileRepository $fileRepository;
    private ValidatorChain $validatorChain;
    private UploadedFile $uploadedFile;

    private CreateFileService $createFileService;

    protected function setUp(): void
    {
        $this->s3Uploader = $this->createMock(FileStorer::class);
        $this->sqsMessageSender = $this->createMock(ProcessFileMessageSender::class);
        $this->fileRepository = $this->createMock(FileRepository::class);
        $this->validatorChain = $this->createMock(ValidatorChain::class);
        $this->uploadedFile = new UploadedFile(__DIR__ . '/test_file.csv', 'test_file.csv');//$this->createMock(UploadedFile::class);

        $this->createFileService = new CreateFileService(
            $this->s3Uploader,
            $this->sqsMessageSender,
            $this->fileRepository,
            $this->validatorChain,
        );
    }

    public function testUploadCsvFile_shouldThrowExceptionBecauseGivenParamIsNull()
    {
        $file = new File('test', 'hash');
        $file->setId('123');
        $this->fileRepository->method('save')->willReturn($file);

        $this->createFileService->uploadCsvFile($this->uploadedFile);

        $this->validatorChain->expects($this->once())->method('validate');//->with($this->uploadedFile);
        $this->fileRepository->expects($this->once())->method('save');//->with(File::class);
        $this->s3Uploader->expects($this->once())->method('store');//->with(File::class);
        $this->sqsMessageSender->expects($this->once())->method('send');//->with($file);
    }
}