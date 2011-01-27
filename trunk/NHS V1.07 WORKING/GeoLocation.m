//
//  GeoLocation.m
//  PushViewControllerAnimated
//
//  Created by User on 12/10/10.
//  Copyright 2010 __MyCompanyName__. All rights reserved.
//

#import "GeoLocation.h"


@implementation GeoLocation

@synthesize longtitude, latitude, updated, geoAware;

-(GeoLocation*) init {
	updated = NO;
	dlocation = [[MyDLocation alloc] init];
	return self;
}
- (CLLocation*) getLocation {
	longtitude = dlocation.currentLocation.coordinate.longitude;
	latitude = dlocation.currentLocation.coordinate.latitude;
	return [[[CLLocation alloc] initWithLatitude:latitude longitude:longtitude] autorelease];
}
- (void) dealloc {
	[dlocation release];
	[super dealloc];
}
@end
