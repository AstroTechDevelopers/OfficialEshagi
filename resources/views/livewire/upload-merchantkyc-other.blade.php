<div>
    @if ($kyc->other_stat == true)
        <div class="row">
            <div class="col-lg-6">
                <img src="{{asset($kyc->other)}}" id="uploadedOtherMerchantkyc" height="120" />
            </div>
        </div>
    @else
        <form wire:submit.prevent="save" enctype="multipart/form-data">
            <div wire:loading wire:target="other">Uploading Other Merchant Kyc...</div>
            <div class="form-group">
                <input type="file" class="form-control" wire:model="other" required/>
                @error('other') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>
            @if ($other)
                Other KYC Doc Preview:
                <img src="{{ $other->temporaryUrl() }}" height="120">
                <br><br>
            @endif

            <button type="submit" class="btn btn-primary">Confirm & Upload</button>
        </form>
    @endif
</div>
