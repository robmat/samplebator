#import <UIKit/UIKit.h>

@interface rhead_sharepoint_ipAppDelegate : NSObject <UIApplicationDelegate> {
    UIWindow *window;
	UINavigationController* navigationController;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;

+ (NSString*) accountsPath;
+ (NSString*) loginDictPath;
+ (UIImage*) imageWithImage:(UIImage*)image scaledToSize:(CGSize)newSize;

@end

