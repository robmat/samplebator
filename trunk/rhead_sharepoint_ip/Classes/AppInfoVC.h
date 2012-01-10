#import <UIKit/UIKit.h>
#import "VCBase.h"
#import <MessageUI/MessageUI.h>

@interface AppInfoVC : VCBase <UIWebViewDelegate, MFMailComposeViewControllerDelegate> {
    IBOutlet UIWebView* webView;
}

@property (nonatomic, retain) UIWebView* webView;

- (IBAction)visitAction: (NSString*) url;
- (IBAction)mailtoAction: (NSString*) url;
- (MFMailComposeViewController*)mailController;

@end
