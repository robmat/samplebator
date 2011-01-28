//
//  SexHealthMapController.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 10/08/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <MapKit/MapKit.h>


@interface SexHealthMapController : UIViewController  <MKMapViewDelegate, UIAlertViewDelegate, UIActionSheetDelegate, UISearchBarDelegate> {
	
	IBOutlet UILabel     *  viewDetailName ;
	IBOutlet UIView      *  viewDetail ;
	IBOutlet MKMapView   *  mapa ;
	IBOutlet UISearchBar *  searchBar ;
	IBOutlet UILabel     *  labelLatitude ;
	
	float minLatitude ;
	float maxLatitude ;
	
	float minLongitude ;
	float maxLongitude ;
	
	NSArray * SexLocations ;
	
	BOOL avanzar ;
	
}

@property (nonatomic, retain) IBOutlet UISearchBar * searchBar ;

-(IBAction)goHome:(id)sender ;
-(NSString *) dataFilePathSex;


@end
