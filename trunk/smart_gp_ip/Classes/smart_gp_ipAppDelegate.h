
//Copyright Applicable Ltd 2011

#import <UIKit/UIKit.h>

@interface smart_gp_ipAppDelegate : NSObject <UIApplicationDelegate> {
    UIWindow *window;
	UINavigationController* navCtrl;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;

- (void) handleNotification: (UILocalNotification*) ln;

@end

