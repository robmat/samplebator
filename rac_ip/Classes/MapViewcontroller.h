//
//  MapViewcontroller.h
//  rac_ip
//
//  Created by User on 3/24/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MapKit/MapKit.h>

@interface MapViewcontroller : UIViewController <MKMapViewDelegate> {

	IBOutlet MKMapView* map;
	
}

- (void) backAction: (id) sender; 

@end
