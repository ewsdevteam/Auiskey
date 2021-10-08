<?php

namespace Webkul\Blog\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Contracts\Validations\Slug;
use Session;
use DB;

class BlogController extends Controller
{

	protected $_config;
	
	protected $categoryRepository;

	public function __construct()
    {
        $this->_config = request('_config');
    }

	public function index(){
		
		return view($this->_config['view']);
	}


	public function create(){
		
		// $categories = $this->categoryRepository->getCategoryTree(null, ['id']);

        return view($this->_config['view']);
	}

	public function store()
    {

        $this->validate(request(), [
            'slug'        => ['required', 'unique:blogs,slug', new \Webkul\Core\Contracts\Validations\Slug],
            'title'        => 'required',
            'image.*'     => 'mimes:jpeg,jpg,bmp,png',
            'description' => 'required',
		]);
		
		$id = DB::table('blogs')->insertGetId([

			'title' => request()->title,
			'content' => request()->description,
			'is_published' => request()->status,
			'image'      => '',
			'slug'       => request()->slug,
			'author'    => auth()->guard('admin')->user()->id,
			'posted_at' => date('Y-m-d H:i:s',time()),
			'meta_title' => request()->meta_title,
			'meta_description' => request()->meta_description,
			'meta_keywords' => request()->meta_keywords
			]);
		
		$path = '';
		if(request()->file('image') != null){

		foreach(request()->file('image') as $image){
			$dir = 'blogs/'.$id;

			if($image != null){
				$path = $image->store($dir);
				DB::table('blogs')->where('id',$id)->update(['image'=>$path]);	
			}
		}

		}

 
		session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Category']));

        return redirect()->route($this->_config['redirect']);
    }

    	public function edit($id){
		$blog = DB::table('blogs')->where('id',$id)->get();
		$categories = DB::table('blog_categories')->join('blog_category_translations','blog_category_translations.blog_category_id','=','blog_categories.id')->get();
		return view($this->_config['view'],compact('blog','categories'));
	}


	public function update($id)
    {
    	
        $this->validate(request(), [
            'slug'        => 'required',
            'title'        => 'required',
            'image.*'     => 'mimes:jpeg,jpg,bmp,png',
            'description' => 'required',
		]);
		
		$bool = DB::table('blogs')->where('id',$id)->update([

			'title' => request()->title,
			'content' => request()->description,
			'is_published' => request()->status,
			'slug'       => request()->slug,
			'author'    => auth()->guard('admin')->user()->id,
			'posted_at' => date('Y-m-d H:i:s',time()),
			'meta_title' => request()->meta_title,
			'meta_description' => request()->meta_description,
			'meta_keywords' => request()->meta_keywords
			
			]);
		
		$path = '';
		
		if(request()->file('image') != null){
			foreach(request()->file('image') as $image){
		
			$dir = 'blogs/'.$id;

			if($image != null){
		
				$path = $image->store($dir);
				DB::table('blogs')->where('id',$id)->update(['image'=>$path]);	
		
			}
		}	
		}
		
		session()->flash('success', 'Blog is updated successfully..!');

        return redirect()->route($this->_config['redirect']);
    }

    public function destroy($id){
    $path = DB::table('blogs')->where('id',$id)->get(['image']);
       if(isset($path[0]->image)){

            Storage::delete($path[0]->image);
       }
       if(isset($path[0]->file)){
            Storage::delete($path[0]->file);
       }
       $bool = DB::table('blogs')->where('id',$id)->delete();
       
       if($bool){
            Session::flash('success','Blog deleted successfully..!');    
       }else{
            Session::flash('error','Blog deleting failed..!');
       }
       return redirect()->back();
    }
    
    public function reviews(){
		return view($this->_config['view']);
	}
	
	public function review_edit($id){
	    
	    $review = DB::table('blog_reviews')->where('id',$id)->get();
	    $review = $review[0];
		return view($this->_config['view'],compact('review'));
	}
	
	public function review_update($id){
	  
	    $bool = DB::table('blog_reviews')->where('id',$id)->update(['status'=>request()->status]);
	    if($bool){
            Session::flash('success','Blog review update successfully..!');    
       }else{
            Session::flash('error','Blog review update failed..!');
       }
		return redirect()->route($this->_config['redirect']);
	}
	public function review_delete($id){
	    
	    $bool = DB::table('blog_reviews')->where('id',$id)->delete();
	    if($bool){
            Session::flash('success','Blog review deleted successfully..!');    
       }else{
            Session::flash('error','Blog review delete failed..!');
       }
		return redirect()->route($this->_config['redirect']);
	}
}