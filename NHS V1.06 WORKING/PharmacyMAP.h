//
//  PharmacyMAP.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 29/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MapKit/MapKit.h>


@interface PharmacyMAP : UIViewController <MKMapViewDelegate, UIAlertViewDelegate, UIActionSheetDelegate, UISearchBarDelegate> {

	IBOutlet UILabel     *  viewDetailName ;
	IBOutlet UIView      *  viewDetail ;
	IBOutlet MKMapView   *  mapa ;
	IBOutlet UISearchBar *  searchBar ;
	IBOutlet UILabel     *  labelLatitude ;
	
	float minLatitude ;
	float maxLatitude ;
	
	float minLongitude ;
	float maxLongitude ;
	
	NSArray * pharmacyLocations ;
	
	BOOL avanzar ;
	
}

@property (nonatomic, retain) IBOutlet UISearchBar * searchBar ;


-(NSString *) dataFilePathPharmacy ;
-(IBAction)goHome:(id)sender;



@end
