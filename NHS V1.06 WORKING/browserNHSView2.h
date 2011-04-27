//
//  browserNHSView2.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 19/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface browserNHSView2 : UIViewController<UIWebViewDelegate> {

	IBOutlet UIWebView * TheWebView;
	IBOutlet UIActivityIndicatorView * activity ;
	UIBarButtonItem* homeButton;
	NSString* urlString;
	NSString* title;
	
}

@property (nonatomic, retain) IBOutlet UIActivityIndicatorView *activity;
@property (nonatomic, retain) IBOutlet UIWebView *TheWebView;
@property (nonatomic, retain) IBOutlet UIBarButtonItem *homeButton;
@property (nonatomic, copy) NSString* urlString;
@property (nonatomic, copy) NSString* title;

-(IBAction)GoHome:(id)sender ;
-(IBAction)GoBack:(id)sender ;
-(IBAction)GoForward:(id)sender ;

@end
