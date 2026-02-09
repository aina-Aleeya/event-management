<div class="bg-white rounded-3xl shadow-2xl border border-gray-200 overflow-hidden">
    
    {{-- Header --}}
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-receipt text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white">Upload Payment Receipt</h2>
                    <p class="text-purple-100 text-sm">Complete your payment by uploading proof</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="p-6 space-y-6">
        
        {{-- Payment Summary --}}
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-5 border-2 border-purple-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-semibold text-gray-600">Amount to Pay</span>
                <span class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                    RM {{ number_format($totalAmount, 2) }}
                </span>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <i class="fa-solid fa-users"></i>
                <span>{{ $registrations->count() }} Participant(s) for {{ $registrations->first()->event->title }}</span>
            </div>
        </div>

        {{-- Upload Instructions --}}
        <div class="bg-blue-50 border-2 border-blue-200 rounded-2xl p-4">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-circle-info text-blue-600 text-xl mt-1"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-bold mb-2">Before uploading your receipt:</p>
                    <ul class="space-y-1 ml-4 list-disc">
                        <li>Ensure the receipt is clear and readable</li>
                        <li>Include transaction date and amount</li>
                        <li>File size should not exceed 2MB</li>
                        <li>Accepted formats: JPG, PNG, JPEG</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Upload Area --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-3">
                Payment Receipt <span class="text-red-500">*</span>
            </label>
            
            @if($receiptImage)
                {{-- Preview --}}
                <div class="relative group">
                    <img src="{{ $receiptImage->temporaryUrl() }}" 
                         alt="Receipt Preview" 
                         class="w-full h-64 object-contain bg-gray-50 rounded-2xl border-2 border-gray-200">
                    
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl flex items-center justify-center">
                        <button type="button" 
                                wire:click="$set('receiptImage', null)"
                                class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold shadow-lg transition-all">
                            <i class="fa-solid fa-trash-can mr-2"></i>
                            Remove & Re-upload
                        </button>
                    </div>
                </div>
            @else
                {{-- Upload Input --}}
                <div class="relative">
                    <input type="file" 
                           wire:model="receiptImage" 
                           accept="image/*"
                           class="hidden" 
                           id="receiptUpload">
                    
                    <label for="receiptUpload" 
                           class="flex flex-col items-center justify-center w-full h-64 border-3 border-dashed border-purple-300 rounded-2xl cursor-pointer bg-purple-50 hover:bg-purple-100 transition-all group">
                        <div class="flex flex-col items-center justify-center py-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg">
                                <i class="fa-solid fa-cloud-arrow-up text-white text-3xl"></i>
                            </div>
                            <p class="mb-2 text-lg font-bold text-gray-700">
                                <span class="text-purple-600">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-sm text-gray-500">PNG, JPG or JPEG (MAX. 2MB)</p>
                        </div>
                    </label>
                </div>

                {{-- Upload Progress --}}
                <div wire:loading wire:target="receiptImage" class="mt-3">
                    <div class="flex items-center gap-3 text-purple-600">
                        <i class="fa-solid fa-spinner fa-spin text-xl"></i>
                        <span class="text-sm font-medium">Processing image...</span>
                    </div>
                </div>
            @endif

            @error('receiptImage')
                <p class="mt-3 text-sm text-red-600 flex items-center gap-2 bg-red-50 p-3 rounded-xl border border-red-200">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Success Message --}}
        @if (session()->has('success'))
            <div class="bg-green-50 border-2 border-green-200 rounded-2xl p-4">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-green-600 text-xl"></i>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Footer Actions --}}
    <div class="bg-gray-50 px-6 py-5 border-t border-gray-200">
        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
            <button type="button"
                    wire:click="backToParticipants"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 font-bold hover:bg-gray-100 transition-all">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Participants
            </button>
            
            <button type="button"
                    wire:click="confirmPayment"
                    wire:loading.attr="disabled"
                    wire:target="confirmPayment"
                    @disabled(!$receiptImage)
                    class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                <span wire:loading.remove wire:target="confirmPayment">
                    <i class="fa-solid fa-check-circle mr-2"></i>
                    Confirm Payment
                </span>
                <span wire:loading wire:target="confirmPayment">
                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                    Processing...
                </span>
            </button>
        </div>
    </div>
</div>

{{-- Additional Info --}}
<div class="mt-6 bg-gradient-to-r from-green-100 to-blue-100 border-2 border-green-200 rounded-2xl p-6">
    <div class="flex items-start gap-4">
        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-shield-halved text-white"></i>
        </div>
        <div>
            <h4 class="font-bold text-gray-800 mb-2">Secure Payment Verification</h4>
            <p class="text-gray-700 text-sm">
                Your payment receipt will be securely stored and your registration will be marked as complete immediately after upload.
            </p>
        </div>
    </div>
</div>