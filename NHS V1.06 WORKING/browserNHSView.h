

#import <UIKit/UIKit.h>


@interface browserNHSView : UIViewController<UIWebViewDelegate> {

	IBOutlet UIWebView * TheWebView;
	IBOutlet UIActivityIndicatorView * activity ;
	
}

@property (nonatomic, retain) IBOutlet UIActivityIndicatorView *activity;
@property (nonatomic, retain) IBOutlet UIWebView *TheWebView;

-(IBAction)GoBack:(id)sender;
-(IBAction)GoForward:(id)sender;
-(IBAction)GoHome:(id)sender;
-(IBAction)Refresh:(id)sender;

@end
