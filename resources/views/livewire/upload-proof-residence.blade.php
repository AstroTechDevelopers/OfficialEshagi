<div>
    @if ($kyc->proof_of_res_stat == true)
        <div class="row">
            <div class="col-lg-6">
                <img src="{{asset($kyc->proof_of_res)}}" id="uploadedProofRes" height="120" />
            </div>
        </div>
    @else
        <form wire:submit.prevent="save" enctype="multipart/form-data">
            <div wire:loading wire:target="proof_of_res">Uploading Proof of residence...</div>
            <div class="form-group">
                <input type="file" class="form-control" wire:model="proof_of_res" required/>
                @error('proof_of_res') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <br>
            @if ($proof_of_res)
                Proof of Residence Preview:
                <img src="{{ $proof_of_res->temporaryUrl() }}" height="120">
                <br><br>
            @endif

            <button type="submit" class="btn btn-primary">Confirm & Upload</button>
        </form>
    @endif
</div>
