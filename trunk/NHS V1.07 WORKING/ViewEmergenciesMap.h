//
//  ViewEmergenciesMap.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 16/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>
//#import <UIkit/UISearchBar.h>
#import <MapKit/MapKit.h>


@interface ViewEmergenciesMap : UIViewController  <MKMapViewDelegate, UIAlertViewDelegate, UIActionSheetDelegate, UISearchBarDelegate> {
	
	IBOutlet UILabel     *  viewDetailName ;
	IBOutlet UIView      *  viewDetail ;
	IBOutlet MKMapView   *  mapa ;
	IBOutlet UISearchBar *  searchBar ;
	IBOutlet UILabel     *  labelLatitude ;
	
	float minLatitude ;
	float maxLatitude ;
	
	float minLongitude ;
	float maxLongitude ;
	
	NSMutableArray * EmergencyLocations ;

	BOOL avanzar ;

}

@property (nonatomic, retain) IBOutlet UISearchBar * searchBar ;
-(IBAction)goHome:(id)sender ;

-(NSString *) dataFilePathEmergency ;



@end