
#import <Foundation/Foundation.h>
#import "DLocationManagerDelegate.h"

@class DLocationManager;

@interface MyDLocation : NSObject <DLocationManagerDelegate>
{
	DLocationManager *locationManager;
	id delegate;
	
	CLLocation *currentLocation;
}

@property (nonatomic, retain) DLocationManager *locationManager;
//@property (nonatomic,assign) id <MyDLocationDelegate> delegate;
@property (nonatomic,assign) id delegate;
@property (copy) CLLocation *currentLocation;

@end
