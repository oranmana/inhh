<ul class="nav nav-tabs" id="assetTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Profile</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="location-tab" data-toggle="tab" href="#location" role="tab" aria-controls="location" aria-selected="false">Location</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="photo-tab" data-toggle="tab" href="#photo" role="tab" aria-controls="photo" aria-selected="false">Image</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    @include('assets.profile')
  </div>
  <div class="tab-pane fade" id="location" role="tabpanel" aria-labelledby="location-tab">
    @include('assets.logs')
  </div>
  <div class="tab-pane fade" id="photo" role="tabpanel" aria-labelledby="photo-tab">
    image upload
  </div>
</div>