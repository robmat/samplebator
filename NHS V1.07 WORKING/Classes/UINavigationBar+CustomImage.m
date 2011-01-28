#import "UINavigationBar+CustomImage.h"

#define kFilename @"GeneralData.plist" 

@implementation UINavigationBar (CustomImage)







- (void) setBackgroundImage:(UIImage*)image {
    if (image == NULL) return;
    UIImageView *imageView = [[UIImageView alloc] initWithImage:image];

	//    imageView.frame = CGRectMake(110,5,100,30);
	
    imageView.frame = CGRectMake(0,0,320,44);
	
    [self addSubview:imageView];
    [imageView release];
}



- (void) clearBackgroundImage {
    NSArray *subviews = [self subviews];
    for (int i=0; i<[subviews count]; i++) {
        if ([[subviews objectAtIndex:i]  isMemberOfClass:[UIImageView class]]) {
			[[subviews objectAtIndex:i] removeFromSuperview];
		}
	}    
}

@end
