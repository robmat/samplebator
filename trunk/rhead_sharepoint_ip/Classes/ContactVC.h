#import <UIKit/UIKit.h>
#import "VCBase.h"
#import <MessageUI/MessageUI.h>

@interface ContactVC : VCBase <UIWebViewDelegate, MFMailComposeViewControllerDelegate> {
	
	IBOutlet UIWebView* webView;
	
}

@property(nonatomic,retain) IBOutlet UIWebView* webView;
 
@end
