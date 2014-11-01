/**
 * Personal Project : Control server
 *
 * MERCANDALLI Jonathan
 */

package com.mercandalli.jarvis.fragment;

import android.app.Fragment;
import android.app.FragmentManager;
import android.os.Bundle;
import android.support.v13.app.FragmentPagerAdapter;
import android.support.v4.view.ViewPager;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.mercandalli.jarvis.Application;
import com.mercandalli.jarvis.R;

public class FileManagerFragment extends Fragment {
	
	private final int NB_FRAGMENT = 2;
	public Fragment listFragment[] = new Fragment[NB_FRAGMENT];
	Application app;
	ViewPager mViewPager;
	FileManagerFragmentPagerAdapter mPagerAdapter;
	View rootView;	
	
	public FileManagerFragment(Application app) {
		this.app = app;
	}
	
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {		
		rootView = inflater.inflate(R.layout.fragment_filemanager, container, false);
		mPagerAdapter = new FileManagerFragmentPagerAdapter(this.getChildFragmentManager());
		
		mViewPager = (ViewPager) rootView.findViewById(R.id.pager);
		mViewPager.setAdapter(mPagerAdapter);		
		
        return rootView;
	}	
	
	public class FileManagerFragmentPagerAdapter extends FragmentPagerAdapter {

		public FileManagerFragmentPagerAdapter(FragmentManager fm) {
			super(fm);
		}
		
		@Override
        public Fragment getItem(int i) {
			Fragment fragment = null;
			switch(i) {
			case 0:		fragment = new FileManagerFragmentServer(FileManagerFragment.this.app); 	break;
			case 1:		fragment = new FileManagerFragmentLocal(FileManagerFragment.this.app);		break;
			default:	fragment = new FileManagerFragmentLocal(FileManagerFragment.this.app);		break;
			}
			listFragment[i] = fragment;
            return fragment;
        }

        @Override
        public int getCount() {
            return NB_FRAGMENT;
        }

        @Override
        public CharSequence getPageTitle(int i) {
        	String title = "null";
			switch(i) {
			case 0:		title = "SERVER";		break;
			case 1:		title = "LOCAL";		break;
			}
			return title;
        }
    }
}
