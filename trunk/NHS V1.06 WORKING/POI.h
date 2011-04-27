//
//  POI.h
//  BigApp1
//
//  Created by Andrew  Farmer on 01/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <MapKit/MapKit.h>

#import <CoreLocation/CoreLocation.h>

@interface POI : NSObject <MKAnnotation> {

	CLLocationCoordinate2D coordinate ;
	NSString * subtitle ;
	NSString * title ;
}


@property (nonatomic,readonly) CLLocationCoordinate2D coordinate ; 
@property (nonatomic,retain) NSString * subtitle ;
@property (nonatomic,retain) NSString * title ;

- (id) initWithCoords: (CLLocationCoordinate2D) coords ;

@end
