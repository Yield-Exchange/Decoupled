<?php

namespace App;

use App\Models\DepositCreditRating;
use App\Models\Organization;
use App\Models\OTP;
use App\Models\RoleType;
use App\Models\UserPassword;
use App\Models\UserPreference;
use App\Models\UsersDemoGraphicData;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;
use App\CustomEncoder;
use Laratrust\Traits\LaratrustUserTrait;
use App\Models\UserApprovalLimit;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    protected $table='users';
    protected $guarded = ['id'];
    protected $fillable=['name','email','account_opening_date','account_status','modified_date','created_by','modified_section','modified_by','failed_login_attempts',
        'account_closure_date','account_closure_reason','last_login','firstname','lastname','is_test','switched_organization_id','is_system_admin','requires_password_update','admin_loggedin_as','admin_loggedin_as_agent','is_waiting'];

    protected $appends = ['role_name','profile_url','role_id','is_super_admin','timezone','formatted_timezone','organization', 'encoded_user_id', "initials"];
    public $timestamps=false;

   protected $with = ['approvalLimit']; //should not be loaded because of demographicData heavy functions

    protected static function boot()
    {
        parent::boot();

//        if ( auth()->check() ) {
//            User::creating(function ($model) {
//                $organization = auth()->user()->organization;
//                $model->is_test = $organization ? $organization->is_test : 0;
//            });
//        }
    }

    public function roleType()
    {
        return $this->belongsToMany(RoleType::class,'user_role_types','user_id',"role_type_id");
    }

    public function role($organization=null)
    {
        $role = $this->belongsToMany(Role::class,'role_user','user_id',"role_id");
        $organization = $organization == null ? $this->organization : $organization;

        if( $organization && $organization->allow_multi_organizations == 1 ) { // just check for those who can switch to multiple organizations
            if( with(clone $role)->where('role_user.organization_id', $organization->id)->count() > 0 ){
                $role = $role->where('role_user.organization_id', $organization->id);
            }
        }
        return $role;
    }

    public function getRoleNameAttribute()
    {
        $role = $this->role;
        return !empty($role[0]) ? $role[0]->display_name : null;
    }

    public function getFormattedTimezoneAttribute()
    {
        return formattedTimezone($this->timezone);
    }

    public function getRoleIdAttribute()
    {
        $role = $this->role;
        return !empty($role[0]) ? $role[0]->id : null;
    }
    public function getIsSuperAdminAttribute()
    {
        $role = $this->role;
        return !empty($role[0]) && $role[0]->name=="system-administrator" || $this->is_system_admin;
    }

    public function getOrganizationAttribute(){
        $allowed_organizations=$this->allowedOrganizations();
        $allowed_organizations_ids = $allowed_organizations->pluck('id')->toArray();
        if($this->switched_organization_id > 0 && in_array($this->switched_organization_id,$allowed_organizations_ids)){ // check if the user has any organization that allows switching
            return $allowed_organizations->where('id',$this->switched_organization_id)->first(); // return the last switched organization
        }

        return $allowed_organizations->first(); // return any organization, they can switch later
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by')->withDefault(['name'=>'','email'=>'']);
    }

    public function depositCreditRating()
    {
        return $this->belongsTo(DepositCreditRating::class,'user_id');
    }

    public function userPassword()
    {
        return $this->hasMany(UserPassword::class,'user_id')->orderBy('created_at','DESC');
    }

    public function otp()
    {
        return $this->hasMany(OTP::class,'user_id');
    }

    public function demographicData($organization=null)
    {
        $return = $this->hasOne(UsersDemoGraphicData::class,'user_id');
        $organization = $organization == null ? $this->organization : $organization;
        if( !empty($organization) && with( clone $return)->where('organization_id',$organization->id)->count() > 0 ){
            $return = $return->where('organization_id',$organization->id);
        }
        return $return->withDefault(['user_id'=>$this->id,'location'=>'','job_title'=>'',
                'department'=>'','phone'=>'','timezone'=>'Central','updated_at'=>null,'created_at'=>null]);
    }

    public function preference()
    {
        return $this->hasMany(UserPreference::class,'user_id','id');
    }

    public function getProfileUrlAttribute()
    {
        if (!empty($this->organization->logo)) {
            return url('/image/' . $this->organization->logo);
        }

        return null;
    }

    public function getTimezoneAttribute()
    {
        return $this->demographicData ? $this->demographicData->timezone : '';
    }

    public function isOrganizationAdmin()
    {
        $organization = $this->organization;
        if (!$organization){
            return false;
        }

        $role = $this->role;
        return !empty($role[0]) && $role[0]->name=="organization-administrator";
    }

    public function userCan($permission){
        $role = Role::find($this->role_id);
        if( $this->is_super_admin || $this->isOrganizationAdmin() ){
            if($this->is_super_admin) {
                if( $role && $role->name == 'system-administrator'){
                    return true;
                }
            }else {
                return true;
            }
        }

        if(!$role){
            return false;
        }

        if($role->isAbleTo($permission)){
            return true;
        }

        return false;
    }

    public function isAbleToLogin(){
        $user = $this;
        $organization = $user->organization;
        return  $user->is_super_admin && $user->account_status == "ACTIVE"
            || $organization && in_array($organization->type,["BANK","DEPOSITOR"]) && $user->account_status == "ACTIVE" && in_array($organization->status,["ACTIVE"]);
    }

    public function getNameAttribute($value)
    {
        if( !empty($this->firstname) || !empty($this->lastname) ) {
            return $this->firstname . ' ' . $this->lastname;
        }
        return $value;
    }

    public function getInitialsAttribute() {
        return  $this->is_super_admin ? ( !empty($this->name) ? $this->name[0] : 'Y' ) : (!empty($this->organization->name[0]) ? $this->organization->name[0] : 'Y');
    }

    public function allowedOrganizations(){
        /*return $this->belongsToMany(Organization::class,'users_organizations','user_id',"organization_id")
                ->whereIn('organizations.status',systemActiveOrganizationStatuses());*/
        return Organization::select([
                'users_organizations.is_default','organizations.*'
            ])->join('users_organizations','users_organizations.organization_id','=','organizations.id')
            ->whereIn('organizations.status',['ACTIVE','PENDING'])
            ->where('users_organizations.user_id',$this->id)
            ->orderBy('organizations.id','ASC')
            ->groupBy('organizations.id')->get();
    }

    public function allowedOrganizationsForSwitch(){
        return Organization::select([
                'organizations.*'
            ])->join('users_organizations','users_organizations.organization_id','=','organizations.id')
            ->whereIn('organizations.status',['ACTIVE','PENDING'])
            ->whereNotIn('organizations.id',[$this->organization->id])
            ->where('users_organizations.user_id',$this->id)
            ->where('organizations.is_test',$this->organization->is_test)
            ->orderBy('organizations.id','ASC')
            ->groupBy('organizations.id')->get();
    }

    public function getEncodedUserIdAttribute()
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }

    public function approvalLimit() {
        return $this->hasOne(UserApprovalLimit::class);
    }

    public function getIsTestAttribute($value)
    {
        return $this->organization ? $this->organization->is_test : '';
    }
}
