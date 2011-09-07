#import <UIKit/UIKit.h>

@interface rhead_sharepoint_ipAppDelegate : NSObject <UIApplicationDelegate> {
    UIWindow *window;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;

+ (NSString*) loginDictPath;

@end

