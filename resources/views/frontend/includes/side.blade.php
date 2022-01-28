<div class="col-lg-4">
					<div class="user-dashboard-link">
					
						<ul class="p-0 m-0 list-unstyled">
						@if(isset(Auth::user()->userData->profile_image) && !empty(Auth::user()->userData->profile_image))
							<li>
							<img class="photo mb-2" style='height: 100%; width: 100%; object-fit: contain; border:1px solid #0089bf' src="{{url(Auth::user()->userData->profile_image)}}" /><br />
							</li>
								@endif
							<li>
								<a href="{{route('frontend.profile')}}" class="{{ request()->is('profile') ? 'active-tab' : '' }}"><i class="far fa-user me-2"></i> My Profile</a>
							</li>
							@if(Auth::user()->userRole->role->slug == 'tutor')
							<li>
								<a href="{{route('frontend.topics')}}" class="{{ request()->is('topics') ? 'active-tab' : '' }}"><i class="far fa-bookmark me-2"></i> Topics</a>
							</li>
							<li>
								<a href="{{route('frontend.videos')}}" class="{{ request()->is('videos') ? 'active-tab' : '' }}"><i class="far fa-bookmark me-2"></i> Videos</a>
							</li>
							@endif
							
							<li>
								<a href="{{route('frontend.studentFavourites')}}" class="{{ request()->is('studentFavourites') ? 'active-tab' : '' }}"><i class="far fa-heart me-2"></i> Favourite</a>
							</li>
							<li>
								<a href="{{route('frontend.mylearningListCourse')}}" class="{{ request()->is('my-mylearning-list') ? 'active-tab' : '' }}"><i class="far fa-bookmark me-2"></i> My Learning</a>
							</li>
							<li>
								<a href="{{route('frontend.myPayment')}}" class="{{ request()->is('my-payment') ? 'active-tab' : '' }}"><i class="fas fa-history me-2"></i> Payment History</a>
							</li>
							
							
						
							<li>
								<a href="{{route('frontend.changePassword')}}" class="{{ request()->is('change-password') ? 'active-tab' : '' }}"><i class="fa fa-lock me-2"></i> Change Password</a>
							</li>
							<li>
								<a  href="javascript:;" onclick="$('#logoutForm').submit()"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
							</li>
						</ul>
					</div>
				</div>