//
//  InWhichCountyAmI.h
//  Polygon
//
//  Created by User on 12/9/10.
//  Copyright 2010 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <MapKit/MapKit.h>

@interface InWhichCountyAmI : NSObject {
	NSMutableDictionary* countyDict;
	NSDictionary* nameDict;
}
@property (retain, readonly) NSDictionary* nameDict;

- (InWhichCountyAmI*) init;
- (NSString*) giveCountyWithLongtitude: (double) lon latitude: (double) lat;
- (CLLocation*) giveCountyCenterWithCountyId: (NSString*) countyId;

@end

@protocol IGeoAware

- (void) updateGeoLon:(double) lon Lat:(double) lat;
@end
