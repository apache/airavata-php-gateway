<!-- partial template variables:
    storageResource - (required, StorageResourceDescription) the storage resource object
    preferences - (optional, UserStoragePreference) the saved preference data
    show - (optional, boolean)
    allowDelete - (optional, boolean)
-->
<!-- String replace is done as Jquery creates problems when using period(.) in id or class. -->
<div id="sr-{{ str_replace( '.', "_", $storageResource->storageResourceId) }}" class="@if(isset( $show) ) @if( !$show) hide @endif @else hide @endif">
<div class="form-group">
    <label class="control-label col-md-3">Login Username</label>

    <div class="col-md-9">
        <input type="text" name="loginUserName" class="form-control"
               value="@if( isset( $preferences) ){{$preferences->loginUserName}}@endif"/>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3">File System Root Location</label>

    <div class="col-md-9">
        <input type="text" name="fileSystemRootLocation" class="form-control"
               value="@if( isset( $preferences) ){{$preferences->fileSystemRootLocation}}@endif"/>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3">Resource Specific Credential Store Token</label>

    <div class="col-md-9">
        <select class="form-control gateway-credential-store-token" name="resourceSpecificCredentialStoreToken" >
            <option value="">Select a Credential Token from Store</option>
            @foreach( $tokens as $token => $description )
                <option value="{{$token}}" @if( isset( $preferences) ) @if( $token == $preferences->resourceSpecificCredentialStoreToken) selected @endif @endif>{{$description}}</option>
            @endforeach
            <option value="">DO-NO-SET</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12 text-center">
        <input type="submit" class="btn btn-primary" value="Save"/>
        <button type="button" class="btn btn-danger remove-user-storage-resource @if(isset( $allowDelete ) ) @if( !$allowDelete) hide @endif @else hide @endif"
            data-toggle="modal"
            data-target="#remove-user-storage-resource-block"
            data-sr-name="{{$storageResource->hostName}}"
            data-sr-id="{{$storageResource->storageResourceId}}">
            Remove
        </button>
    </div>
</div>

</div>
