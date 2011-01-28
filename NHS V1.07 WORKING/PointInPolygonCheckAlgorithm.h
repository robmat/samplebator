//
//  PolongInPolygonCheckAlgorithm.h
//  Polygon
//
//  Created by User on 12/6/10.
//  Copyright 2010 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreLocation/CoreLocation.h>


@interface PointInPolygonCheckAlgorithm : NSObject {
	int n;			
	NSMutableArray* polygonx;	
	NSMutableArray* polygony;
	double rx, ry;		
	double tmpx, tmpy;  
	double px, py;		
	int k;	
}
@property (readwrite, assign) double px, py;

- (void) setUp: (NSString*) path;
- (double) ownerityXX: (double)xx XY:(double)xy YX:(double)yx YY:(double)yy ZX:(double)zx ZY:(double)zy;
- (double) detXX: (double)xx XY:(double)xy YX:(double)yx YY:(double)yy ZX:(double)zx ZY:(double)zy;
- (double) crossingAX:(double)ax AY:(double) ay BX:(double)bx BY:(double)by;
- (BOOL) isInside;
- (CLLocation*) givePolygonCenter;
@end
