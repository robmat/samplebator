//
//  GeoLocation.h
//  PushViewControllerAnimated
//
//  Created by User on 12/10/10.
//  Copyright 2010 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreLocation/CoreLocation.h>
#import "InWhichCountyAmI.h"
#import <MapKit/MapKit.h>
#import "MyDLocation.h"

@interface GeoLocation : NSObject <CLLocationManagerDelegate> {
	double longtitude, latitude;
	BOOL updated;
	id <IGeoAware> geoAware;
	MyDLocation* dlocation;
}
@property (readwrite) double longtitude, latitude;
@property (readonly) BOOL updated;
@property (readwrite, retain) id <IGeoAware> geoAware;

- (GeoLocation*) init;
- (CLLocation*) getLocation;
@end
