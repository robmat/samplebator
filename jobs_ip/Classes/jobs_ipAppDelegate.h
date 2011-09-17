#import <UIKit/UIKit.h>

@interface jobs_ipAppDelegate : NSObject <UIApplicationDelegate> {
    UIWindow *window;
	UINavigationController* navigationController;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;
@property (nonatomic, retain) UINavigationController* navigationController;

@end

