<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\BlobSharedAccessSignatureHelper;

class AzureStorageController extends Controller
{
    public function generateUploadUrl()
    {
        // Cấu hình kết nối Azure Blob Storage
        $accountName = env('AZURE_STORAGE_ACCOUNT_NAME');
        $accountKey = env('AZURE_STORAGE_ACCOUNT_KEY');
        $containerName = env('AZURE_STORAGE_CONTAINER_NAME');
        $blobName = request()->fileName;

        if (!$blobName) {
            return response()->json(["error" => 'File name not found'], 404);
        }

        // Create BlobSharedAccessSignatureHelper instance
        $blobSasHelper = new BlobSharedAccessSignatureHelper($accountName, $accountKey);

        // Generate a shared access signature for the blob
        $signedResource = 'b'; // Blob resource type
        $resourceName = $containerName . '/' . $blobName;
        $signedPermissions = 'rw'; // Read permission
        $signedExpiry = now()->addMinutes(10);

        $sasToken = $blobSasHelper->generateBlobServiceSharedAccessSignatureToken(
            $signedResource,
            $resourceName,
            $signedPermissions,
            $signedExpiry,
        );

        // Construct the full URI with the generated SAS token
        $uploadUrl = "https://$accountName.blob.core.windows.net/$containerName/$blobName?$sasToken";

        // Trả về SAS Token và URL upload
        return response()->json([
            'uploadUrl' => $uploadUrl, 
        ]);
    }

    //         // Tạo kết nối BlobService
    //         $connectionString = "DefaultEndpointsProtocol=https;AccountName=$accountName;AccountKey=$accountKey";
    //         $blobClient = BlobRestProxy::createBlobService($connectionString);
    
    // //         $content = $blobClient->getBlob($containerName, $blobName);
    // //         // Cấu hình header trả về
    // // return response($content->getContentStream())
    // // ->header('Content-Type', 'image/png'); // Đảm bảo rằng Content-Type đúng với loại ảnh
    
    
    //         // Liệt kê tất cả các blob trong container
    //         $blobs = $blobClient->listBlobs($containerName);
    //         // Trả về danh sách tên blob
    //         $blobNames = [];
    //         foreach ($blobs->getBlobs() as $blob) {
    //             $blobNames[] = $blob->getName();
    //         }
    
    //         // return response()->json($blobNames);
}
