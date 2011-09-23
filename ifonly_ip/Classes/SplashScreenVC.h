#import <UIKit/UIKit.h>
#import "VCBase.h"

@interface SplashScreenVC : VCBase {
	NSTimer* timer;
}

- (IBAction)skipAction;
- (void)timerAction;

@end
