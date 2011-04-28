//
//  StopSmokingMapViewController.h
//  PushViewControllerAnimated
//
//  Created by User on 4/27/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MapKit/MapKit.h>

@interface StopSmokingMapViewController : UIViewController <MKMapViewDelegate, UIAlertViewDelegate, UISearchBarDelegate> {
	IBOutlet UILabel     *  viewDetailName ;
	IBOutlet UIView      *  viewDetail ;
	IBOutlet MKMapView   *  mapa ;
	IBOutlet UISearchBar *  searchBar ;
	IBOutlet UILabel     *  labelLatitude ;
	
	float minLatitude ;
	float maxLatitude ;
	
	float minLongitude ;
	float maxLongitude ;
	
	NSArray * smokingLocations ;
	
	BOOL avanzar ;
}

@property (nonatomic, retain) UISearchBar*  searchBar;

@end
